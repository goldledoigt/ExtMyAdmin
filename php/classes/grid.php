<?php

class grid extends table {

	public $columns;
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
		// $result['rows'] = parent::getDataAt($this->rows->id);
		$result['success'] = true;
		return $result;
	}

	function create() {
		$id = parent::insertData($this->rows);
		$result['rows'] = parent::getDataAt($id);
		$result['success'] = true;
		return $result;
	}

	function update() {
		parent::updateData($this->rows);
		$result['rows'] = parent::getDataAt($this->rows->id);
		$result['success'] = true;
		return $result;
	}

	function read() {
		$result = array();
		parent::setConfig($this->readConfig);
		$result['success'] = true;
		$result['rows'] = parent::getData();
		$result['columns'] = $this->getColumns();
		$result['count'] = parent::getCount();
		$result['metaData'] = $this->getMetaData();
		return $result;
	}

	function getColumns() {
		$coluns = array();
		$this->columns = parent::getColumns();
		$c =& $this->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			$columns[$i]['header'] = $c[$i]['COLUMN_NAME'];
			$columns[$i]['dataIndex'] = $c[$i]['COLUMN_NAME'];
			$columns[$i]['sortable'] = true;
		}
		return $columns;
	}

	function getMetaData() {
		$meta = array();
		$meta['root'] = 'rows';
		$meta['idProperty'] = 'id';
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