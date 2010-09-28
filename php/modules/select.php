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


class SelectModule {
  public $db;
  public $query;
  public $fields;
  public $tables;
  public $count;

  public function __construct() {
    $this->db = new Database();
  }

  public function exec($query, $parseQuery = false) {
    $rows = array();
    $this->query = $query;
    if ($parseQuery) $this->parseQuery();
    if (stristr($query, 'INSERT INTO ') !== false) {
      $res = $this->db->insert($query);
    } else {
      $res = $this->db->query($query);
    }
    if ($res) {
      $this->count = $this->db->numrows($res);
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
