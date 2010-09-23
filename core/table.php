<?php

class table {

	private $db;
	private $error = false;
	private $query = array();
	private $fields;
	public $name;

	function __construct($db) {
		$this->db = $db;
	}

	function execQuery($query) {
		$json = array();
		$this->query['string'] = $query;
		$this->parseQuery();
		$json['data'] = $this->read();
		// $json['data'] = array();
		$json['count'] = $this->getCount();
		$json['metaData']['fields'] = $this->getFields();
		$json['columns'] = $this->getColumns();

		$json['metaData']['root'] = 'data';
		$json['metaData']['idProperty'] = 'id';
		$json['metaData']['totalProperty'] = 'count';
		$json['metaData']['successProperty'] = 'success';
        $json['metaData']['messageProperty'] = 'log';

		if ($this->error) {
			$json['success'] = false;
			$json['log']['query'] = cleanString($this->error['query']);
			$json['log']['message'] = cleanString($this->error['message']);
		} else {
			$json['log']['query'] = cleanString($this->query['string']);
			$json['log']['message'] = 'query successfully executed';
			$json['success'] = true;
		}

		return $json;
	}

	function parseQuery() {
		$this->query['select'] = stristr($this->query['string'], 'FROM', true);
		$from = explode('FROM', strtoupper($this->query['string']));
		$from = explode('LIMIT', 'FROM '.$from[1]);
		$this->query['from'] = $from[0];
	}

	function getType($type) {
		if (strstr($type, 'int'))
			return 'Number';
		else if (strstr($type, 'varchar'))
			return 'String';
		return '';
	}

	function getCount() {
		if ($res = $this->db->query('SELECT count(*) AS count '.$this->query['from'])) {
			// print 'SELECT count(*) AS count '.$this->query['from'];
			$row = $this->db->getObj($res);
			return $row->count;
		}
		return false;
	}

	function getFields() {
		$rows = array();
		for ($i = 0, $s = sizeof($this->fields); $i < $s; $i++) {
			$rows[$i]['name'] = $this->fields[$i];
			// $rows[$i]['type'] = $this->getType($row['Type']);
		}
		return $rows;
	}

	function getColumns() {
		$rows = array();
		for ($i = 0, $s = sizeof($this->fields); $i < $s; $i++) {
			$rows[$i]['header'] = $this->fields[$i];
			$rows[$i]['dataIndex'] = $this->fields[$i];
			$rows[$i]['editor'] = 'new Ext.form.TextField()';
		}
		return $rows;
	}

	function read() {
		$rows = array();
		if ($res = $this->db->query($this->query['string'])) {
			for ($i = 0; $row = $this->db->getAssoc($res); $i++) {
				foreach ($row as $field => $value) {
					if ($i === 0) $this->fields[] = $field;
					$rows[$i][$field] = cleanString($value);
				}
			}
		} else $this->catchError();
		return $rows;
	}

	function update($data) {
		$query = 'UPDATE '.$this->name.' SET ';
		$count = 0;
		foreach ($data as $key => $value) {
			if ($key != 'id') {
				$query .= $key.' = "'.$value.'"';
				if ($count < sizeof($data) - 2)
					$query .= ', ';
			}
			$count++;
		}
		$query .= ' WHERE id = '.$data->id;
		$this->db->query($query);
		$json = array();
		$json['log']['query'] = $query;
		$json['log']['message'] = 'query successfully executed';
		$json['success'] = true;
		return $json;
	}

	function create() {
		$json = array();
		$query = "INSERT INTO $this->name VALUES()";
		$this->db->query($query);
		$id = $this->db->last_insert();
		$res = $this->db->query("SELECT * FROM $this->name WHERE id = $id");
		$json['data'] = $this->db->getAssoc($res);
		$json['log']['query'] = $query;
		$json['log']['message'] = 'query successfully executed';
		$json['success'] = true;
		return $json;
	}

	function destroy($data) {
		$json = array();
		$query = "DELETE FROM $this->name WHERE id = $data";
		$this->db->query($query);
		$json['data'] = array();
		$json['log']['query'] = $query;
		$json['log']['message'] = 'query successfully executed';
		$json['success'] = true;
		return $json;
	}

	function catchError() {
		$this->error = $this->db->getError();
		$tab = explode(':', $this->error['query']);
		$this->error['query'] = $tab[1];
		$tab = explode(':', $this->error['message']);
		$this->error['message'] = $tab[1];
	}

}

?>