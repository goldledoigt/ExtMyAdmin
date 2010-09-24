<?php

class tree {

	public $node;
	public $type;

	function __construct($config) {
		$this->node = $config[0];
		$this->type = $config[1];
	}

	function read() {
		if ($this->type === 'database')
			return $this->getSchemas();
		else if ($this->type === 'schema')
			return $this->getTables();
		else if ($this->type === 'table')
			return $this->getColumns();
	}

	function getSchemas() {
		$nodes = array();
		$d = new database();
		$schemas = $d->getSchemas();
		for ($i = 0, $l = sizeof($schemas); $i < $l; $i++) {
			$nodes[$i] = array(
				'type' => 'schema'
				,'id' => $schemas[$i]['SCHEMA_NAME']
				,'text' => $schemas[$i]['SCHEMA_NAME']
				,'iconCls' => 'icon-node-schema'
				,'leaf' => false
			);
		}
		return $nodes;
	}

	function getTables() {
		$nodes = array();
		$s = new schema($this->node);
		$tables = $s->getTables();
		for ($i = 0, $l = sizeof($tables); $i < $l; $i++) {
			$nodes[$i] = array(
				'type' => 'table'
				,'id' => $tables[$i]['TABLE_NAME']
				,'text' => $tables[$i]['TABLE_NAME']
				,'iconCls' => 'icon-node-table'
				,'leaf' => false
			);
		}
		return $nodes;
	}

	function getColumns() {
		$nodes = array();
		$t = new table($this->node);
		$columns = $t->getColumns();
		for ($i = 0, $l = sizeof($columns); $i < $l; $i++) {
			$nodes[$i] = array(
				'type' => 'column'
				,'id' => $columns[$i]['COLUMN_NAME']
				,'text' => $columns[$i]['COLUMN_NAME']
				,'iconCls' => 'icon-node-column'
				,'leaf' => true
			);
		}
		return $nodes;
	}

}

?>