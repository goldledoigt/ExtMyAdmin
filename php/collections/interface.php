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
  protected $_vars;

  /**
   * Internal class properties design.
   *
   * @property array $_design
   */
  protected $_design;

  /**
   * Magic __set method.
   *
   * @method __set
   * @param string $name Variable name
   * @param mixed $value Variable $value
   */
  public function __set($name, $value) {
    $method_name = '_set_'.$name;
    if ($this->__is_in_design($name) and
        method_exists($this, $method_name)) {
      $value = call_user_func(array($this, $method_name), $value);
    }
    $this->_vars[$name] = $value;
  }

  /**
   * Magic __get method.
   *
   * @method __get
   * @param string $name Variable name
   * @return mixed Variable value
   */
  public function __get($name) {
    $method_name = '_get_'.$name;
    if ($this->__is_in_design($name) and
        method_exists($this, $method_name)) {
      return (call_user_func(array($this, $method_name)));
    }
    else if (empty($this->_vars[$name]) === false) {
      return ($this->_vars[$name]);
    }
    return (null);
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
    return (!empty($this->_design[$name]));
  }
}