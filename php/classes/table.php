<?php

class table {

	public $name;
	public $select;
	public $columns = false;
	public $start;
	public $limit;
	public $sort = false;
	public $dir = false;
	public $primaryKey = false;

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
		$order = $this->getSortInfo();
		$query = "SELECT * FROM $this->name ORDER BY ".$order['field']." ".$order['direction']." LIMIT $this->start, $this->limit";
		$rows = $this->select->exec('accelrh', $query);
		return $rows;
	}

	function getDataAt($field, $index) {
		$where = "WHERE $field = $index";
		$query = "SELECT * FROM $this->name WHERE id = $index";
		$rows = $this->select->exec('accelrh', $query);
		return $rows[0];
	}

	function getColumns() {
		if ($this->columns === false) {
			$query = "SELECT * FROM COLUMNS WHERE TABLE_NAME = '$this->name'";
			$this->columns = $this->select->exec('information_schema', $query);
		}
		return $this->columns;
	}

	function getCount() {
		$query = "SELECT COUNT(*) AS count FROM $this->name";
		$rows = $this->select->exec('accelrh', $query);
		return $rows[0]['count'];
	}

	function getSortInfo() {
		$dir = ($this->dir === 'ASC' or $this->dir === 'DESC') ? $this->dir : 'ASC';
		$field = ($this->sort and strlen($this->sort)) ? $this->sort : $this->getPrimaryKey();
		return array('field' => $field, 'direction' => $dir);
	}

	function getPrimaryKey() {
		if ($this->columns === false) $this->getColumns();
		for ($i = 0, $l = sizeof($this->columns); $i < $l; $i++) {
			$c =& $this->columns[$i];
			if ($c['COLUMN_KEY'] === 'PRI') {
				$this->primaryKey = $c['COLUMN_NAME'];
			}
		}
		return $this->primaryKey;
	}

	function updateData($data) {
		$set = array();
		$pk = $this->getPrimaryKey();
		foreach ($data as $key => $value)
			if ($key !== $pk) $set[] = "$key = '$value'";
		$set = implode(", ", $set);
		$query = "UPDATE $this->name SET $set WHERE $pk = ".$data->{$pk};
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
		$pk = $this->getPrimaryKey();
		$query = "DELETE FROM $this->name WHERE $pk = ".$data->{$pk};
		$this->select->exec('accelrh', $query);
	}

}

?>