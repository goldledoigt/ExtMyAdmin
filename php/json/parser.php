<?php

/**
 * Json parser class.
 *
 * @class JsonParser
 */
class JsonParser {
  /**
   * Encode given data to Json string.
   *
   * @static
   * @method encode
   * @param mixed $data Data to encode
   * @param string Json encoded data
   */
  public static function encode($data) {
    $data = Charset::convert($data, 'UTF-8');
    return (json_encode($data));
  }

  /**
   * Decode given string to PHP data.
   *
   * @static
   * @method decode
   * @param string $str Json string
   * @param mixed PHP data
   */
  public static function decode($str='') {
    $str = Charset::convert($str, 'UTF-8');
    return (json_decode($str, true));
  }
}