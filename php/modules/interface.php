<?php

/**
 * Module interface.
 *
 * @class IModule
 */
abstract class IModule extends JsonError {
  /**
   * Database.
   *
   * @property Database $_db
   */
  protected $_db;

  /**
   * Module interface constructor.
   *
   * @constructor
   * @param Database $database Database instance
   */
  public function __construct($database) {
    $this->_db = $database;
  }

  /**
   * Return Database instance.
   *
   * @method get_db
   * @return Database Module database instance
   */
  public function get_db() {
    return ($this->_db);
  }

  /**
   * Call module method.
   *
   * @method call
   * @param string $method Method name
   * @param array $args Arguments
   */
  public function call($method, array $args) {
    $method = 'callable_'.basename($method);
    if (method_exists($this, $method)) {
      return (call_user_func_array(array($this, $method), $args));
    }
    return ($this->get_error('This action is not avalaible.'));
  }
}
