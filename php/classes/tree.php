<?php

class tree {

	public $node;
	public $type;
	public $schema;

	function __construct($config) {
		$this->node = $config[0];
		$this->type = $config[1];
		if (isset($config[2])) $this->schema = $config[2];
		if (isset($config[3])) $this->new = $config[3];
	}

	function update() {
		if ($this->type === 'table')
			return $this->renameTable();
	}

	function renameTable() {
		return table::rename($this->node, $this->new);
	}

	function destroy() {
		if ($this->type === 'schema')
			return $this->destroySchema();
		else if ($this->type === 'table')
			return $this->destroyTable();
	}

	function destroySchema() {
		database::destroySchema($this->node);
		$node = array();
		$node['success'] = true;
		return $node;
	}

	function destroyTable() {
		schema::destroyTable($this->schema, $this->node);
		$node = array();
		$node['success'] = true;
		return $node;
	}

	function create() {
		if ($this->type === 'schema')
			return $this->createSchema();
		else if ($this->type === 'table')
			return $this->createTable();
	}

	function createSchema() {
		database::createSchema($this->node);
		$node = array();
		$node['iconCls'] = "icon-node-schema";
		$node['id'] = $this->node;
		$node['text'] = $this->node;
		$node['type'] = 'schema';
		return $node;
	}

	function createTable() {
		schema::createTable($this->schema, $this->node);
		$node = array();
		$node['iconCls'] = "icon-node-table";
		$node['id'] = $this->node;
		$node['text'] = $this->node;
		$node['type'] = 'table';
		return $node;
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
		$t = new table($this->schema, $this->node);
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