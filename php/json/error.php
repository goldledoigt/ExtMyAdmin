<?php

/**
 * JSON error class
 *
 * @class json_error
 */
class json_error {
  /**
   * Error(s) store
   *
   * @property {array} $_error
   */
  protected $_error = array();

  /**
   * Check if error were generated
   *
   * @method getError
   * @return {boolean} True if there is some errors else false
   */
  public function getError() {
    return (!empty($this->_error[0]));
  }

  /**
   * Format error to JSON, show to stdout and exit.
   *
   * @method _show_error
   * @param {string} $msg Error message
   */
  protected function _show_error($msg) {
    $class_name = get_parent_class($this);
    $json = '{"error": "From '.$class_name.' error: "'.$msg.'"}';
    exit($json);
  }
}
