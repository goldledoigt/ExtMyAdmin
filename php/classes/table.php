<?php

class table {

	public $name;
	public $select;
	public $columns;

	function __construct($name) {
		$this->name = $name;
		$this->select = new select();
		// $this->columns = $this->getColumns();
	}

	// function read() {
	// 	$result = array();
	// 	$result['data'] = $this->getData();
	// 	$result['columns'] = $this->getColumns();
	// 	$result['count'] = $this->getCount();
	// 	$result['metaData'] = $this->getMetaData();
	// 	return $result;
	// }

	function getData() {
		$query = "SELECT * FROM $this->name LIMIT 0, 30";
		$rows = $this->select->exec('accelrh', $query);
		return $rows;
	}

	function getColumns() {
		$query = "SELECT * FROM COLUMNS WHERE TABLE_NAME = '$this->name'";
		$rows = $this->select->exec('information_schema', $query);
		return $rows;
	}

	function getCount() {
		$query = "SELECT COUNT(*) AS count FROM $this->name";
		$rows = $this->select->exec('accelrh', $query);
		return $rows[0]['count'];
	}

	// function getFields() {
	// 	return $this->getColumns();
	// }

	// function getMetaData() {
	// 	$meta = array();
	// 	$meta['root'] = 'data';
	// 	$meta['idProperty'] = 'id';
	// 	$meta['totalProperty'] = 'count';
	// 	$meta['successProperty'] = 'success';
	// 	$meta['messageProperty'] = 'log';
	// 	$meta['fields'] = $this->getFields();
	// 	return $meta;
	// }

}

?>