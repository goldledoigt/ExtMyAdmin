<?php

require dirname(__FILE__).'/interface.php';

/**
 * ORM class.
 *
 * @class orm
 */
class orm extends json_error {
  /**
   * MySQL orm constant.
   *
   * @property {string} mysql
   */
  const mysql = 'mysql';

  /**
   * Current orm class in use.
   *
   * @property {iorm} $_orm_class
   */
  protected $_orm_class;

  /**
   * Call ORM instance method with args
   *
   * @method __call
   * @param {string} $name ORM method name
   * @param {array} $args Method arguments
   * @return {mixed} ORM class method result
   */
  public function __call($name, $args) {
    if (empty($this->_orm_class) or
        method_exists($this->_orm_class, $name) === false) {
      $this->_show_error('ORM Method does not exists "'.$name.'"');
    }
    return (call_user_func_array(array($this->_orm_class, $name), $args));
  }

  /**
   * new orm()
   *
   * @constructor
   * @param {orm::const} $class_name Class name
   */
  public function __construct($class_name='') {
    if (empty($class_name) and
        defined('settings::orm_class')) {
      $class_name = constant('settings::orm_class');
    }
    $class_name = basename($class_name);
    if (defined('self::'.$class_name) === false) {
      $this->_show_error('You need to configure settings::orm parameters in settings.php.');
    }
    if ($this->_set_orm_instance($class_name) === false) {
      $this->_show_error('This classname orm does not exists "'.$class_name.'"');
    }
  }

  /**
   * Try to instantiate user choosed ORM class
   *
   * @method _set_orm_instance
   * @param {string} $class_name ORM class name
   * @return {boolean} True if succeed else false
   */
  protected function _set_orm_instance($class_name) {
    $class_name = basename($class_name);
    if (class_exists($class_name) === false and
        file_exists(dirname(__FILE__).'/'.$class_name.'.php')) {
      require dirname(__FILE__).'/'.$class_name.'.php';
    }
    if (class_exists($class_name) === false) {
      return (false);
    }
    $this->_orm_class = new $class_name();
    return (true);
  }
}