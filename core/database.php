<?php

class database {

	private $db;

	function __construct($db) {
		$this->db = $db;
	}

	function create($params) {
		$name =& $params->name;
		$query = "CREATE DATABASE $name";
		$this->db->query($query);
		$node = array();
		$node['iconCls'] = "icon-database";
		$node['id'] = $name;
		$node['text'] = $name;
		$node['type'] = 'schema';
		return $node;
	}

	function read() {
		$rows = array();
		$res = $this->db->query('SHOW DATABASES');
		for ($i = 0; $row = $this->db->getAssoc($res); $i++) {
			$rows[$i]['iconCls'] = "icon-database";
			$rows[$i]['id'] = $row['Database'];
			$rows[$i]['text'] = $row['Database'];
			$rows[$i]['type'] = 'schema';
		}
		return $rows;
	}
	
}

?>