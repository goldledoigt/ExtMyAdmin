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
   * Current database
   *
   * @property string $_db_name
   */
  protected $_db_name;

  /**
   * Database interface constructor
   *
   * @constructor
   * @param string $db_host Database hostname
   * @param string $db_user Database username
   * @param string $db_pass Database password
   * @param string $db_name Database name
   */
  public function __construct($db_host='', $db_user='',
                              $db_pass='', $db_name='') {
    foreach (array('db_host', 'db_user', 'db_pass', 'db_name') as $param) {
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
   * Format string with params
   *
   * @method format
   * @param string $query Query
   * @param ... $args Arguments list
   * @return string Formated query with params if succeed else empty string if failed
   */
  public function format() {
    $num_args = func_num_args();
    if (empty($num_args)) {
      return (false);
    }
    $query = call_user_func_array('sprintf', func_get_args());
    return ($query);
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
   * @method get_schemas
   * @param string $database Database name
   */
  abstract public function get_schemas($database);

  /**
   * Create database schema.
   *
   * @method create_schema
   * @param string $name Schema name
   * @param string $database Database name
   */
  abstract public function create_schema($name, $database);

  /**
   * Destroy database schema.
   *
   * @method destroy_schema
   * @param string $name Schema name
   * @param string $database Database name
   */
  abstract public function destroy_schema($name, $database);
}