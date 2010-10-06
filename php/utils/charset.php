<?php

/**
 * Charset class.
 *
 * @class Charset
 */
class Charset {
  /**
   * Convert given data to charset.
   *
   * @static
   * @method convert
   * @param mixed $data Data to convert
   * @return mixed Data converted to given charset
   */
  public static function convert($data, $charset='UTF-8') {
    if (is_array($data)) {
      foreach ($data as &$value) {
        if (is_string($value)) {
          $value = mb_convert_encoding($value, 'UTF-8');
        }
        if (is_array($value)) {
          $value = self::convert($value);
        }
      }
    } else if (is_string($data)) {
      $data = mb_convert_encoding($data, 'UTF-8');
    }
    return ($data);
  }
}
