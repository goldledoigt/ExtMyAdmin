<?php

class schema {

	public $name;

	function __construct($name) {
		$this->name = $name;
	}

	function getTables() {
		$s = new select();
		$query = "SELECT * FROM TABLES WHERE TABLE_SCHEMA = '$this->name'";
		$rows = $s->exec('information_schema', $query);
		return $rows;
	}

}

?>