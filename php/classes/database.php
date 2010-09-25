<?php

class database {

	function __construct() {
		
	}

	function destroySchema($name) {
		$s = new select();
		$query = "DROP DATABASE $name";
		$s->exec('information_schema', $query);
	}

	function createSchema($name) {
		$s = new select();
		$query = "CREATE DATABASE $name";
		$s->exec('information_schema', $query);
	}

	function getSchemas() {
		$s = new select();
		$query = "SELECT * FROM SCHEMATA ORDER BY SCHEMA_NAME";
		$rows = $s->exec('information_schema', $query);
		return $rows;
	}

}

?>