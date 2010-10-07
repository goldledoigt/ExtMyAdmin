<?php

/**
 * Module interface.
 *
 * @class IModule
 */
abstract class IModule extends JsonError {
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
