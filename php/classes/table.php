<?php

class table {

	public $name;
	public $select;
	public $columns;
	public $start;
	public $limit;
	public $sort;
	public $dir;

	function __construct($name) {
		$this->name = $name;
		$this->select = new select();
	}

	function setConfig($config) {
		$this->start = $config[0];
		$this->limit = $config[1];
		$this->sort = $config[2];
		$this->dir = $config[3];
	}

	function getData($index=false) {
		$query = "SELECT * FROM $this->name ORDER BY $this->sort $this->dir LIMIT $this->start, $this->limit";
		$rows = $this->select->exec('accelrh', $query);
		return $rows;
	}

	function getDataAt($index) {
		$where = "WHERE id = $index";
		$query = "SELECT * FROM $this->name WHERE id = $index";
		$rows = $this->select->exec('accelrh', $query);
		return $rows[0];
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

	function getSortInfo() {
		return array(
			'field'=>$this->sort
			,'direction'=>$this->dir
		);
	}

	function updateData($data) {
		$set = array();
		foreach ($data as $key => $value)
			if ($key !== 'id') $set[] = "$key = '$value'";
		$set = implode(", ", $set);
		$query = "UPDATE $this->name SET $set WHERE id = $data->id";
		$this->select->exec('accelrh', $query);
	}

	function insertData($data) {
		$field = array();
		foreach ($data as $key => $value) {
			$field['name'] = $key;
			$field['value'] = $value;
		}
		$query = "INSERT INTO $this->name (".$field['name'].") VALUES ('".$field['value']."')";
		return $this->select->exec('accelrh', $query);
	}

	function deleteData($data) {
		$query = "DELETE FROM $this->name WHERE id = $data->id";
		$this->select->exec('accelrh', $query);
	}

}

?>