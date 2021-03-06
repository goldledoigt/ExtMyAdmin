<?php

require dirname(__FILE__).'/api.php';

/**
 * MySQL class
 *
 * @class Mysql
 * @extends MysqlApi
 */
class Mysql extends MysqlApi {
  /**
   * MySQL connection link
   *
   * @property stdClass $__link
   */
  private $__link;

  /**
   * MySQL current query
   *
   * @property resource $__query
   */
  private $__query;

  /**
   * New MySQL instance.
   *
   * @constructor
   * @param string $db_host Database hostname
   * @param string $db_user Database username
   * @param string $db_pass Database password
   * @param string $db_name Database name
   */
  public function __construct($db_host='', $db_user='', $db_pass='') {
    parent::__construct($db_host, $db_user, $db_pass);
    format::set_addslashes('mysql_real_escape_string');
  }

  /**
   * Return current MySQL link.
   *
   * @method _get_link
   * @return ressource MySQL link
   */
  protected function _get_link() {
    return ($this->__link);
  }

  /**
   * Connect to MySQL database.
   *
   * @method connect
   */
  public function connect() {
    $this->__link = mysql_connect($this->_db_host, $this->_db_user,
                                  $this->_db_pass) or
      $this->_show_error('cannot connect');
    mysql_set_charset('utf8', $this->__link);
  }

  /**
   * Execute MySQL query.
   *
   * @method execute
   * @param string $query MySQL query
   * @return mixed MySQL resource if succeed else false
   */
  public function execute($query) {
    if ($result = mysql_query($query, $this->__link)) {
      $this->__query = $result;
      return ($result);
    } else {
      $this->_show_error('cannot execute query');
    }
    return (false);
  }

  /**
   * Get query rows number.
   *
   * @method get_num
   * @param mixed $query Query
   * @return mixed False if failed else rows number
   */
  public function get_num($query=null) {
    if (empty($query)) {
      return (mysql_num_rows($this->__query));
    }
    return (false);
  }

  /**
   * Return query results formated into an associated array.
   *
   * @method gets_assoc
   * @param mixed $resource MySQL query resource or query string
   * @param array $keys Result key mapping array
   * @return array Results
   */
  public function gets_assoc($resource, array $keys=array()) {
    if (is_string($resource)) {
      $resource = $this->execute($resource);
    }
    $results = array();
    while ($result = mysql_fetch_assoc($resource)) {
      if (empty($keys) === false) {
        $result = $this->merge($result, $keys);
      }
      $results[] = Charset::convert($result, 'UTF-8');
    }
    return ($results);
  }

  /**
   * Return query results formated into an array.
   *
   * @method gets_row
   * @param mixed $resource MySQL query resource or query string
   * @param array $keys Result key mapping array
   * @return array Results
   */
  public function gets_row($resource, array $keys=array()) {
    if (is_string($resource)) {
      $resource = $this->execute($resource);
    }
    $results = array();
    while ($result = mysql_fetch_row($resource)) {
      if (empty($keys) === false) {
        $result = $this->merge($result, $keys);
      }
      $results[] = Charset::convert($result, 'UTF-8');
    }
    return ($results);
  }

  /**
   * Close current connection to MySQL server.
   *
   * @method close
   */
  public function close() {
    if (empty($this->__link) === false) {
      return (mysql_close($this->__link) or
              $this->_show_error('cannot close connection'));
    }
    return (false);
  }
}