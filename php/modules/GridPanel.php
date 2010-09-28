<?php

class GridPanel {

	public $store;

	public $rows;
	public $readConfig;
	public $dateFormat = 'Y-m-d';
	public $dateTimeFormat = 'Y-m-d h:i:s';

	function __construct($config, $store) {
		if (is_object($config[0])) {
			$schema = $config[0]->schema;
			$name = $config[0]->table;
			$this->rows = $config[0]->rows;
		} else {
			$schema = array_shift($config);
			$name = array_shift($config);
			$this->readConfig = $config;
		}
		// var_dump($schema, $name);
		// var_dump($config);
		$this->store = new $store($schema, $name);
		// parent::__construct($schema, $name);
	}

	function destroy() {
		$this->store->deleteData($this->rows);
		// parent::deleteData($this->rows);
		$result['rows'] = array(key($this->rows) => $this->rows->{key($this->rows)});
		$result['success'] = true;
		return $result;
	}

	function create() {
		$key = $this->store->insertData($this->rows);
		// $key = parent::insertData($this->rows);
		$result['rows'] = $this->store->getDataAt($this->store->getPrimaryKey(), $key);
		// $result['rows'] = parent::getDataAt($this->getPrimaryKey(), $key);
		$result['success'] = true;
		return $result;
	}

	function update() {
		$this->store->updateData($this->rows);
		// parent::updateData($this->rows);
		$pk = $this->store->getPrimaryKey();
		// $pk = $this->getPrimaryKey();
		$result['rows'] = $this->store->getDataAt($pk, $this->rows->{$pk});
		// $result['rows'] = parent::getDataAt($pk, $this->rows->{$pk});
		$result['success'] = true;
		return $result;
	}

	function read() {
		$result = array();
		$result['columns'] = $this->getColumns();
		$this->store->setConfig($this->readConfig);
		// parent::setConfig($this->readConfig);
		$result['rows'] = $this->store->getData();
		$result['count'] = $this->store->getCount();
		// $result['rows'] = parent::getData();
		// $result['count'] = parent::getCount();
		$result['metaData'] = $this->getMetaData();
		$result['success'] = true;
		return $result;
	}

	function getColumns() {
		$columns = array();
		$this->store->getColumns();
		$this->store->getPrimaryKey();
		// parent::getColumns();
		// parent::getPrimaryKey();
		$c =& $this->store->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			$style = '';

			$columns[$i]['editor'] = array('xtype' => 'textfield');

			if ($c[$i]['DATA_TYPE'] === 'int') {
				$columns[$i]['xtype'] = 'numbercolumn';
				$columns[$i]['align'] = 'right';
				$columns[$i]['format'] = '0';
			} else if ($c[$i]['DATA_TYPE'] === 'text') {
				$columns[$i]['editor'] = array(
					'xtype' => 'textarea'
				);
			} else if ($c[$i]['DATA_TYPE'] === 'boolean') {
				$columns[$i]['width'] = 30;
				$columns[$i]['editor'] = array(
					'xtype' => 'combo'
					,'typeAhead' => true
					,'triggerAction' => 'all'
					,'lazyRender' => true
					,'mode' => 'local'
					,'store' => array('YES', 'NO')
				);
			} else if ($c[$i]['DATA_TYPE'] === 'date') {
				$columns[$i]['width'] = 80;
				$columns[$i]['xtype'] = 'datecolumn';
				$columns[$i]['format'] = $this->dateFormat;
				$columns[$i]['editor'] = array(
					'xtype' => 'datefield'
					,'format' => $this->dateFormat
				);
			} else if ($c[$i]['DATA_TYPE'] === 'datetime' or $c[$i]['DATA_TYPE'] === 'timestamp') {
				$columns[$i]['width'] = 115;
				$columns[$i]['xtype'] = 'datecolumn';
				$columns[$i]['format'] = $this->dateTimeFormat;
				$columns[$i]['editor'] = array(
					'xtype' => 'datefield'
					,'format' => $this->dateTimeFormat
				);
			}

			if ($c[$i]['COLUMN_NAME'] === $this->store->getPrimaryKey()) {
				$style = 'style="color:red"';
				$columns[$i]['editable'] = false;
				$columns[$i]['width'] = 60;
			}			

			$columns[$i]['header'] = "<b $style>".$c[$i]['COLUMN_NAME'].'</b>';
			$columns[$i]['dataIndex'] = $c[$i]['COLUMN_NAME'];
			$columns[$i]['sortable'] = true;
			if ($tooltip = $this->getColumnToolTip($c[$i]))
				$columns[$i]['tooltip'] = $tooltip;
		}
		return $columns;
	}

	function getColumnToolTip($column) {
		$tooltip = array();
		if (isset($column['COLUMN_TYPE']) and strlen($column['COLUMN_TYPE']))
			$tooltip[] = '<tr><td>Type:</td><td>'.$column['COLUMN_TYPE'].'</td></tr>';
		if (isset($column['IS_NULLABLE']) and strlen($column['IS_NULLABLE']))
			$tooltip[] = '<tr><td>Nullable:</td><td>'.$column['IS_NULLABLE'].'</td></tr>';
		if (isset($column['COLLATION_NAME']) and strlen($column['COLLATION_NAME']))
			$tooltip[] = '<tr><td>Collation:</td><td>'.$column['COLLATION_NAME'].'</td></tr>';
		if (isset($column['EXTRA']) and strlen($column['EXTRA']))
			$tooltip[] = '<tr><td>Extra:</td><td>'.$column['EXTRA'].'</td></tr>';
		if (sizeof($tooltip))
			$tooltip = "<table class='tooltip-column-details'>".implode('', $tooltip).'</table>';
		else $tooltip = false;
		return $tooltip;
	}

	function getMetaData() {
		$meta = array();
		$meta['root'] = 'rows';
		$meta['idProperty'] = $this->store->getPrimaryKey();
		// $meta['idProperty'] = $this->getPrimaryKey();
		$meta['totalProperty'] = 'count';
		$meta['successProperty'] = 'success';
		$meta['sortInfo'] = $this->store->getSortInfo();
		// $meta['sortInfo'] = parent::getSortInfo();
		// $meta['messageProperty'] = 'log';
		$meta['fields'] = $this->getFields();
		return $meta;
	}

	function getFields() {
		$fields = array();
		$c =& $this->store->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			if ($c[$i]['COLUMN_NAME'] === $this->store->getPrimaryKey()) {
				$fields[$i]['type'] = 'int';
			}
			if ($c[$i]['DATA_TYPE'] === 'int')
				$fields[$i]['type'] = 'int';
			else if ($c[$i]['DATA_TYPE'] === 'varchar' or $c[$i]['DATA_TYPE'] === 'text')
				$fields[$i]['type'] = 'string';
			else if ($c[$i]['DATA_TYPE'] === 'date') {
				$fields[$i]['type'] = 'date';
				$fields[$i]['dateFormat'] = $this->dateFormat;
			} 	else if ($c[$i]['DATA_TYPE'] === 'datetime' or $c[$i]['DATA_TYPE'] === 'timestamp') {
				$fields[$i]['type'] = 'date';
				$fields[$i]['dateFormat'] = $this->dateTimeFormat;
			}			
			$fields[$i]['name'] = $c[$i]['COLUMN_NAME'];
			if (isset($c[$i]['COLUMN_DEFAULT']) and strlen($c[$i]['COLUMN_DEFAULT']))
				$fields[$i]['defaultValue'] = $c[$i]['COLUMN_DEFAULT'];
			
			// if (strlen($c[$i]['COLUMN_DEFAULT']) or is_numeric($c[$i]['COLUMN_DEFAULT']))				
				// $fields[$i]['defaultValue'] = $c[$i]['COLUMN_DEFAULT'];
				// if ($fields[$i]['defaultValue'] === null) $fields[$i]['defaultValue'] = 'NULL';
		}
		return $fields;
	}

}


?>