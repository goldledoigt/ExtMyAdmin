<?php

/**
 * MySQL class
 *
 * @class mysql
 */
class mysql extends iorm {
  /**
   * MySQL connection link
   *
   * @property {stdClass} $__link
   */
  private $__link;

  /**
   * MySQL current query
   *
   * @property {string} $__current_query
   */
  private $__current_query;

  /**
   * new mysql()
   *
   * @constructor
   * @param {string} $db_host Database hostname
   * @param {string} $db_user Database username
   * @param {string} $db_pass Database password
   * @param {string} $db_name Database name
   */
  public function __construct($db_host='', $db_user='',
                              $db_pass='', $db_name='') {
    parent::__construct($db_host, $db_user, $db_pass, $db_name);
    $this->select_db();
  }

  public function connect() {
    $this->__link = mysql_connect($this->_db_host, $this->_db_user,
                                  $this->_db_pass) or
      $this->_show_error('cannot connect');
  }

  public function select_db($db = false) {
    if (empty($db) === false) {
      $this->_db_name = $db;
    }
    if (empty($this->_db_name) === false) {
      mysql_select_db($this->_db_name, $this->__link) or
        $this->_show_error('cannot select database');
    }
    return (true);
  }

  public function insert($query) {
    $this->__current_query = $query;
    if ($this->query($query)) {
      return ($this->last_insert());
    }
    return (false);
  }

  public function query($query) {
    $this->_error = array();
    $this->__current_query = $query;
    if ($result = mysql_query($query, $this->__link)) {
      return ($result);
    } else {
      $this->_show_error('cannot execute query');
    }
    return (false);
  }

  public function last_insert() {
    return (mysql_insert_id($this->__link));
  }

  public function getObj($resource) {
    return (mysql_fetch_object($resource));
  }

  public function getAssoc($resource) {
    return (mysql_fetch_assoc($resource));
  }

  public function getRows($ressource){
    return (mysql_fetch_row($ressource));
  }

  public function getArray($result) {
    if ($this->numrows($result)) {
      $array = mysql_fetch_array($result);
      return ($array);
    } else {
      return (false);
    }
  }

  public function numrows($resource) {
    return (mysql_num_rows($resource));
  }

  public function free($resource) {
    return (mysql_free_result($resource) or
            $this->_show_error('cannot free resource'));
  }

  public function close() {
    return (mysql_close($this->__link) or
            $this->_show_error('cannot close connection'));
  }

  /**
   * Show error on stdout
   *
   * @method _show_error
   * @param {string} $str Error message
   */
  protected function _show_error($msg) {
    $this->_error['query'] = ':'.chr(13).$this->__current_query.chr(13).chr(13);
    $this->_error['message'] = 'MySQL server returns :'.chr(13).mysql_error();
    $msg .= ':'.chr(13).$this->__current_query.chr(13).chr(13);
    $msg .= 'MySQL server returns:'.chr(13).mysql_error();
    die($msg);
  }
}