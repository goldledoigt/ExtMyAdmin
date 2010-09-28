<?php

class schema {

	public $name;

	function __construct($name) {
		$this->name = $name;
	}

	function createTable($schema, $name) {
		$s = new select();
		$query = "CREATE TABLE $name (id INT)";
		$s->exec($schema, $query);
	}

	function destroyTable($schema, $name) {
		$s = new select();
		$query = "DROP TABLE $name";
		$s->exec($schema, $query);
	}

	function getTables() {
		$s = new select();
		$query = "SELECT * FROM TABLES WHERE TABLE_SCHEMA = '$this->name'";
		$rows = $s->exec('information_schema', $query);
		return $rows;
	}

}

?>