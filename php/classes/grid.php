<?php

class grid extends table {

	public $rows;
	public $readConfig;

	function __construct($config) {
		if (is_object($config[0])) {
			$name = $config[0]->table;
			$this->rows = $config[0]->rows;
		} else {
			$name = array_shift($config);
			$this->readConfig = $config;
		}
		parent::__construct($name);
	}

	function destroy() {
		parent::deleteData($this->rows);
		$result['rows'] = array(key($this->rows) => $this->rows->{key($this->rows)});
		$result['success'] = true;
		return $result;
	}

	function create() {
		$key = parent::insertData($this->rows);
		$result['rows'] = parent::getDataAt($this->getPrimaryKey(), $key);
		$result['success'] = true;
		return $result;
	}

	function update() {
		parent::updateData($this->rows);
		$pk = $this->getPrimaryKey();
		$result['rows'] = parent::getDataAt($pk, $this->rows->{$pk});
		$result['success'] = true;
		return $result;
	}

	function read() {
		$result = array();
		$result['columns'] = $this->getColumns();
		parent::setConfig($this->readConfig);
		$result['rows'] = parent::getData();
		$result['count'] = parent::getCount();
		$result['metaData'] = $this->getMetaData();
		$result['success'] = true;
		return $result;
	}

	function getColumns() {
		$columns = array();
		parent::getColumns();
		parent::getPrimaryKey();
		$c =& $this->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			$style = '';
			if ($c[$i]['COLUMN_NAME'] === $this->primaryKey) {
				$style = 'style="color:red"';
			}
			$columns[$i]['header'] = "<b $style>".$c[$i]['COLUMN_NAME'].'</b><br />'.$c[$i]['COLUMN_TYPE'];
			$columns[$i]['dataIndex'] = $c[$i]['COLUMN_NAME'];
			$columns[$i]['sortable'] = true;
		}
		return $columns;
	}

	function getMetaData() {
		$meta = array();
		$meta['root'] = 'rows';
		$meta['idProperty'] = $this->getPrimaryKey();
		$meta['totalProperty'] = 'count';
		$meta['successProperty'] = 'success';
		$meta['sortInfo'] = parent::getSortInfo();
		// $meta['messageProperty'] = 'log';
		$meta['fields'] = $this->getFields();
		return $meta;
	}

	function getFields() {
		$fields = array();
		$c =& $this->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			$fields[$i]['name'] = $c[$i]['COLUMN_NAME'];
		}
		return $fields;
	}

}


?>