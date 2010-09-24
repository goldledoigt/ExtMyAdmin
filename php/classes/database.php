<?php

class database {

	function __construct() {
		
	}

	function getSchemas() {
		$s = new select();
		$query = "SELECT * FROM SCHEMATA";
		$rows = $s->exec('information_schema', $query);
		return $rows;
	}

}

?>