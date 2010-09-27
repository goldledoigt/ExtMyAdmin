<?php

class TableStructure {

	public $name;
	public $select = false;
	public $columns = false;
	public $columnNames;
	public $start;
	public $limit;
	public $sort = false;
	public $dir = false;
	public $primaryKey = false;

	function __construct($schema, $name) {
		$this->schema = $schema;
		$this->name = $name;
		$this->select = new select();
		$this->columnNames = array('COLUMN_NAME', 'DATA_TYPE', 'CHARACTER_MAXIMUM_LENGTH', 'COLUMN_DEFAULT', 'IS_NULLABLE', 'NUMERIC_PRECISION', 'COLUMN_TYPE');
	}

	function getColumnNames($type) {
		if ($type === 'list') {
			$cols = array();
			foreach ($this->columnNames as $name)
				$cols[] = "'".$name."'";
		} else if ($type === 'string') {
			$cols = $this->columnNames;
		}
		return implode(',', $cols);
	}

	function rename($oldName, $newName) {
		if (!isset($this->select)) $this->select = new select();
		$query = "RENAME TABLE $oldName TO $newName";
		$rows = $this->select->exec('information_schema', $query);
		return $newName;
	}

	function setConfig($config) {
		$this->start = $config[0];
		$this->limit = $config[1];
		$this->sort = $config[2];
		$this->dir = $config[3];
	}
/*
	function getColumnList() {
		$cols = array();
		foreach ($this->columns as $col)
			$cols[] = $col['COLUMN_NAME'];
		return implode(',', $cols);
	}
*/
	function getData($index=false) {
/*
		$columns = $this->getColumnList();
		$query = "SELECT $columns FROM COLUMNS WHERE TABLE_SCHEMA = '$this->schema' AND TABLE_NAME = '$this->name'";
		$rows = $this->select->exec('information_schema', $query);
		$this->count = $this->select->count;
		return $rows;
*/
		$cols = $this->getColumnNames('string');
		$order = $this->getSortInfo();
		$order = ($order['field']) ? "ORDER BY ".$order['field']." ".$order['direction'] : "";
		$query = "SELECT $cols FROM COLUMNS WHERE TABLE_SCHEMA = '$this->schema' AND TABLE_NAME = '$this->name' ORDER BY ORDINAL_POSITION";
		$rows = $this->select->exec('information_schema', $query);
		for ($i = 0, $l = sizeof($rows); $i < $l; $i++) {
			$r =& $rows[$i];
			// var_dump($r);
			if (is_numeric($rows[$i]['NUMERIC_PRECISION'])) {
				$r['CHARACTER_MAXIMUM_LENGTH'] = $r['NUMERIC_PRECISION'] + 1;
			}
			if ($r['DATA_TYPE'] === 'text') {
				$r['CHARACTER_MAXIMUM_LENGTH'] = '';
			}
			$r['UNSIGNED'] = (stristr($r['COLUMN_TYPE'], 'unsigned') !== false) ? true : false;
		}
		return $rows;
	}

	function getDataAt($field, $index) {
		$where = "WHERE $field = $index";
		$query = "SELECT $this->columnNames FROM $this->name WHERE $field = $index";
		$rows = $this->select->exec('information_schema', $query);
		return $rows[0];
	}

	function getColumns() {
		// return $this->columns;
		$cols = $this->getColumnNames('string');
		if ($this->columns === false) {
			$query = "
				SELECT $cols FROM COLUMNS
				WHERE TABLE_SCHEMA = 'information_schema'
				AND TABLE_NAME = 'COLUMNS'
				AND COLUMN_NAME IN (".$this->getColumnNames('list').")
			";
			$this->columns = $this->select->exec('information_schema', $query);
		}
		array_pop($this->columns);
		array_pop($this->columns);
		for ($i = 0, $l = sizeof($this->columns); $i < $l; $i++) { 
			$c =& $this->columns[$i];
			if ($c['COLUMN_NAME'] === 'IS_NULLABLE') {
				$c['DATA_TYPE'] = 'boolean';
				// var_dump($c['DATA_TYPE']);
			}
		}
		return $this->columns;
	}

	function getCount() {
		// return $this->count;
		$query = "SELECT COUNT(*) AS count FROM COLUMNS WHERE TABLE_SCHEMA = '$this->schema' AND TABLE_NAME = '$this->name'";
		$rows = $this->select->exec('information_schema', $query);
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
			if (isset($c['COLUMN_KEY']) and $c['COLUMN_KEY'] === 'PRI') {
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
		$this->select->exec('information_schema', $query);
	}

	function insertData($data) {
		$field = array();
		foreach ($data as $key => $value) {
			$field['name'] = $key;
			$field['value'] = $value;
		}
		$query = "INSERT INTO $this->name (".$field['name'].") VALUES ('".$field['value']."')";
		return $this->select->exec('information_schema', $query);
	}

	function deleteData($data) {
		$pk = $this->getPrimaryKey();
		$query = "DELETE FROM $this->name WHERE $pk = ".$data->{$pk};
		$this->select->exec('information_schema', $query);
	}

}

?>