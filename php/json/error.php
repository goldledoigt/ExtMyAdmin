<?php

/**
 * JSON error class
 *
 * @class JsonError
 */
class JsonError {
  /**
   * Error(s) store
   *
   * @property array $_error
   */
  protected $_error = array();

  /**
   * Check if error were generated
   *
   * @method get_error
   * @param string $msg Error message
   * @param string $attr Error key attribute (default to error)
   * @return string Json encoded error
   */
  public function get_error($msg, $attr='error') {
    $class_name = get_class($this);
    $error = array($attr => 'From "'.$class_name.'" error: "'.$msg.'"');
    $this->_error[] = $error;
    return ($error);
  }

  /**
   * Format error to JSON, show to stdout and exit.
   *
   * @method _show_error
   * @param string $msg Error message
   * @param string $attr Error key attribute (default to error)
   */
  protected function _show_error($msg, $attr='error') {
    $json = $this->get_error($msg, $attr);
    exit(JsonParser::encode($json));
  }
}
