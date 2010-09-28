<?php

require dirname(__FILE__).'/format.php';
require dirname(__FILE__).'/interface.php';

/**
 * Database class.
 *
 * @class Database
 */
class Database extends JsonError {
  /**
   * MySQL database constant.
   *
   * @property string mysql
   */
  const mysql = 'mysql';

  /**
   * Current database class in use.
   *
   * @property IDatabase $_database_class
   */
  protected $_database_class;

  /**
   * new database()
   *
   * @constructor
   * @param Database::const $class_name Class name
   */
  public function __construct($class_name='') {
    if (empty($class_name) and
        defined('settings::database_class')) {
      $class_name = constant('settings::database_class');
    }
    $class_name = basename($class_name);
    if (defined('self::'.$class_name) === false) {
      $this->_show_error('You need to configure settings::database parameters in settings.php.');
    }
    if ($this->_set_database_instance($class_name) === false) {
      $this->_show_error('This classname database does not exists "'.$class_name.'"');
    }
  }

  /**
   * Call DATABASE instance method with args
   *
   * @method __call
   * @param string $name DATABASE method name
   * @param array $args Method arguments
   * @return mixed DATABASE class method result
   */
  public function __call($name, $args) {
    if (empty($this->_database_class) or
        method_exists($this->_database_class, $name) === false) {
      $this->_show_error('DATABASE Method does not exists "'.$name.'"');
    }
    return (call_user_func_array(array($this->_database_class, $name), $args));
  }

  /**
   * Try to instantiate user choosed DATABASE class
   *
   * @method _set_database_instance
   * @param string $class_name DATABASE class name
   * @return boolean True if succeed else false
   */
  protected function _set_database_instance($class_name) {
    $class_name = basename($class_name);
    $file_path = dirname(__FILE__).'/'.$class_name.'/core.php';
    if (class_exists($class_name) === false and
        file_exists($file_path)) {
      require $file_path;
    }
    if (class_exists($class_name) === false) {
      return (false);
    }
    $this->_database_class = new $class_name();
    return (true);
  }
}