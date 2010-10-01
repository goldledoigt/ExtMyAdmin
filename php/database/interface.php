<?php

/**
 * Database Interface class
 *
 * @class IDatabase
 */
abstract class IDatabase extends JsonError {
  /**
   * Database hostname
   *
   * @property string $_db_host
   */
  protected $_db_host;

  /**
   * Database username
   *
   * @property string $_db_user
   */
  protected $_db_user;

  /**
   * Database user password
   *
   * @property string $_db_pass
   */
  protected $_db_pass;

  /**
   * Database interface constructor
   *
   * @constructor
   * @param string $db_host Database hostname
   * @param string $db_user Database username
   * @param string $db_pass Database password
   */
  public function __construct($db_host='', $db_user='', $db_pass='') {
    foreach (array('db_host', 'db_user', 'db_pass') as $param) {
      $class_param = '_'.$param;
      if (empty($$param) and defined('settings::'.$param)) {
        $this->$class_param = constant('settings::'.$param);
      } else {
        $this->$class_param = $$param;
      }
    }
    $this->connect();
  }

  /**
   * Merge the source array by replacing keys, values pair.
   *
   * @method merge
   * @param array $src Source array
   * @param array $keys Source key => new key
   * @return array Merged array
   */
  public function merge(array $src, array $keys, $keep_not_found=false) {
    $dest = array();
    foreach ($src as $key => $value) {
      if (empty($keys[$key]) === false) {
        $dest[$keys[$key]] = $value;
      } else if ($keep_not_found === true) {
        $dest[$key] = $value;
      }
    }
    return ($dest);
  }

  /**
   * Close current object connection to database.
   *
   * @destructor
   * @method __destruct
   */
  public function __destruct() {
    $this->close();
  }

  /**
   * Get database schemas.
   *
   * @abstract
   * @method get_databases
   * @param string $database Database name
   */
  abstract public function get_databases($database);

  /**
   * Return database tables.
   *
   * @method get_tables
   * @param string $database Database name
   * @return array Results
   */
  abstract public function get_tables($database);

  /**
   * Return table columns.
   *
   * @method get_columns
   * @param string $database Database name
   * @param string $table Table name
   * @return array Results
   */
  abstract public function get_columns($database, $table);
}