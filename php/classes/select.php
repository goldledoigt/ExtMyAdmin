<?php

function iexplode($separator, $string, $limit = false ) {
   $len = strlen($separator);
   for ($i = 0; ; $i++) {
       if ( ($pos = stripos( $string, $separator )) === false || ($limit !== false && $i > $limit - 2 ) ) {
           $result[$i] = $string;
           break;
       }
       $result[$i] = substr( $string, 0, $pos );
       $string = substr( $string, $pos + $len );
   }
   return $result;
}



class select {
	
	public $query;
	public $fields;
	public $tables;
	
	function __construct() {
		$this->db = new mysql();
	}

	function exec($db, $query, $parseQuery = false) {
		$rows = array();
		$this->query = $query;
		if ($parseQuery) $this->parseQuery();
		$this->db->select_db($db);
		if (stristr($query, 'INSERT INTO ') !== false) {
			$res = $this->db->insert($query);
		} else {
			$res = $this->db->query($query);
		}
		
		if ($res) {
			if (is_resource($res)) {
				while ($row = $this->db->getAssoc($res)) {
					$rows[] = $row;
				}
			} else {
				$rows = $res;
			}
		}
		return $rows;
	}

	function parseQuery() {
		$a = explode('FROM', $this->query);
		$before_from = $a[0];
		$after_from = $a[1];
		$this->getFieldsFromQuery($before_from);
		$this->getTablesFromQuery($after_from);
	}

	function getTablesFromQuery($query) {
		if (stripos($query, ' WHERE ') !== false) {
			$a = explode(' WHERE ', $query);
		} else if (stripos($query, ' SORT BY ') !== false) {
			$a = explode(' SORT BY ', $query);
		} else if (stripos($query, ' LIMIT ') !== false) {
			$a = explode(' LIMIT ', $query);
		}
		if (isset($a)) {
			$query = $a[0];
		}
		$a = explode(',', $query);
		$this->tables = $this->getValuesAndAlias($a);
	}

	function getFieldsFromQuery($query) {
		if (stripos($query, 'DISTINCT') !== false) {
			$a = explode('SELECT DISTINCT ', $query);
		} else {
			$a = explode('SELECT ', $query);
		}
		$a = explode(',', $a[1]);
		$this->fields = $this->getValuesAndAlias($a);
	}

	function getValuesAndAlias($tab) {
		$results = array();
		for($i = 0, $l = sizeof($tab); $i < $l; $i++) {
			$v = trim($tab[$i]);
			if (stripos($v, ' AS ') !== false) {
				$b = iexplode(' AS ', $v);
			} else if (stripos($v, ' ') !== false) {
				$b = explode(' ', $v);
			}
			if (isset($b)) {
				$results[$i]['name'] = trim($b[0]);
				$results[$i]['alias'] = trim($b[1]);
			} else {
				$results[$i]['name'] = trim($v);
			}
		}
		return $results;
	}
	
}

function dumpArray($array) {
	foreach ($array as $item) {
		foreach ($item as $key => $value)
			print "$key: $value\n";
		print "------------------\n";
	}
}
/*
header('Content-Type:text/plain');
$query = "SELECT DISTINCT toto, titi as tutu, paf pof FROM tata, puf pouf WHERE id = 3 AND user LIKE '%jean%'";
print $query."\n";
$s = new select();
$s->exec($query);

print "\nTABLES:\n";
dumpArray($s->tables);
print "\nFIELDS:\n";
dumpArray($s->fields);

print "\n\n";

$query = "SELECT * FROM tata";
print $query."\n";
$s = new select();
$s->exec($query);

print "\nTABLES:\n";
dumpArray($s->tables);
print "\nFIELDS:\n";
dumpArray($s->fields);
*/


?>