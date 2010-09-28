<?php

class DatabaseModule {

	function __construct() {

	}

	function destroySchema($name) {
		$s = new SelectModule();
        $query = $s->db->destroySchema($name);
		$s->exec($query);
	}

	function createSchema($name) {
		$s = new SelectModule();
        $query = $s->db->createSchema($name);
		$s->exec($query);
	}

	function getSchemas() {
		$s = new SelectModule();
        $query = $s->db->getSchemas();
		$rows = $s->exec($query);
		return ($rows);
	}

}

?>