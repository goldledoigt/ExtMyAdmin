<?php

/**
 * Collection interface class.
 *
 * @class ICollection
 */
abstract class ICollection {
  /**
   * Internal variables.
   *
   * @property array $_vars
   */
  protected $_vars = array();

  /**
   * Internal class properties design.
   *
   * @property array $_design
   */
  protected $_design;

  /**
   * Internal database instance.
   *
   * @property Database $__db
   */
  private $__db;

  /**
   * Return Database instance.
   *
   * @method get_db
   * @return Database Module database instance
   */
  public function get_db() {
    if (empty($this->__db)) {
      $this->__db = DB::get();
    }
    return ($this->__db);
  }

  /**
   * Set design var value.
   *
   * @method set
   * @param string $name Var name
   * @param mixed $value Var value
   * @return mixed Value if succeed else false
   */
  public function set($name, $value) {
    $method_name = '_set_'.$name;
    if ($this->__is_in_design($name) and
        method_exists($this, $method_name)) {
      $this->_vars[$name] = call_user_func(array($this, $method_name), $value);
      return ($this->_vars[$name]);
    }
    return (false);
  }

  /**
   * Get design var value.
   *
   * @method get
   * @param string $name Var name
   * @return mixed Var value
   */
  public function get($name) {
    $method_name = '_get_'.$name;
    if ($this->__is_in_design($name) and
        empty($this->_vars[$name]) === false) {
      if (method_exists($this, $method_name)) {
        return (call_user_func(array($this, $method_name)));
      }
      return ($this->_vars[$name]);
    }
    return (null);
  }

  /**
   * Get design var html escaped value.
   *
   * @method gethtml
   * @param string $name Var name
   * @return mixed Var value escaped
   */
  public function gethtml($name) {
    $value = $this->get($name);
    if (is_string($value)) {
      return (htmlentities($value, ENT_QUOTES, 'UTF-8'));
    }
    return ($value);
  }

  /**
   * Set design.
   *
   * @method set_design
   * @param array $values Values
   */
  public function set_design(array $values) {
    $this->_design = $values;
  }

  /**
   * Get design.
   *
   * @method get_design
   * @return array Design
   */
  public function get_design() {
    return ($this->_design);
  }

  /**
   * Return design variable.
   *
   * @method get
   * @return array Results
   */
  public function gets() {
    return ($this->_vars);
  }

  /**
   * Test if variable name is in design.
   *
   * @private
   * @method __is_in_design
   * @param string $name Variable name
   * @return boolean True if succeed else false
   */
  private function __is_in_design($name) {
    return (in_array($name, $this->_design));
  }
}