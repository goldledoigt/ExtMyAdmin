<?php

class grid extends table {

	public $columns;

	function __construct($config=false) {
		parent::__construct("client"/*$config->table*/);
	}

	function read() {
		$result = array();
		$result['success'] = true;
		$result['rows'] = $this->getData();
		$result['columns'] = $this->getColumns();
		$result['count'] = $this->getCount();
		$result['metaData'] = $this->getMetaData();
		return $result;
	}

	function getData() {
		return parent::getData();
	}

	function getColumns() {
		$coluns = array();
		$this->columns = parent::getColumns();
		$c =& $this->columns;
		for ($i = 0, $l = sizeof($c); $i < $l; $i++) {
			$columns[$i]['header'] = $c[$i]['COLUMN_NAME'];
			$columns[$i]['dataIndex'] = $c[$i]['COLUMN_NAME'];
		}
		return $columns;
	}

	function getCount() {
		return parent::getCount();
	}

	function getMetaData() {
		$meta = array();
		$meta['root'] = 'rows';
		$meta['idProperty'] = 'id';
		$meta['totalProperty'] = 'count';
		$meta['successProperty'] = 'success';
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