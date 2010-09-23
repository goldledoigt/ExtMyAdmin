<?php

class schema {

	private $db;
	private $name;

	function __construct($db) {
		$this->db = $db;
		// $this->name = $name;
	}

	function read() {
		$rows = array();
		$res = $this->db->query('SHOW TABLES');
		for ($i = 0; $row = $this->db->getArray($res); $i++) {
			$rows[$i]['leaf'] = true;
			$rows[$i]['iconCls'] = "icon-table";
			$rows[$i]['id'] = $row[0];
			$rows[$i]['text'] = $row[0];
			$rows[$i]['type'] = 'table';
		}
		return $rows;
	}
	
}

?>