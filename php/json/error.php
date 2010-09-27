<?php

/**
 * JSON error class
 *
 * @class json_error
 */
class json_error {
  /**
   * Format error to JSON, show to stdout and exit
   */
  protected function _show_error($msg) {
    $class_name = get_parent_class($this);
    $json = '{"error": "From '.$class_name.' error: "'.$msg.'"}';
    exit($json);
  }
}
