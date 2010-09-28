<?php

/**
 * Format queries class.
 * Example usage:
 *  $query = Format::set('SELECT `%s`, `%s` FROM `%s` WHERE `%s`=%d',
 *                       'f1', 'f2', 't1', 'f3', 72)
 *
 * @class Format
 */
class Format {
  /**
   * Put this to true to exit when a parameter is undefined
   * or types missmatch.
   *
   * @property {boolean} strict
   */
  const strict = false;

  /**
   * Error when given array arg is not the expected one.
   *
   * @property {string} array_arg_error
   */
  const array_arg_error = 'Format array argument error.';

  /**
   * Error when given integer arg is not the expected one.
   *
   * @property {string} integer_arg_error
   */
  const integer_arg_error = 'Format integer argument error.';

  /**
   * Error when given float arg is not the expected one.
   *
   * @property {string} float_arg_error
   */
  const float_arg_error = 'Format float argument error.';

  /**
   * Error when given string arg is not the expected one.
   *
   * @property {string} string_arg_error
   */
  const string_arg_error = 'Format string argument error.';

  /**
   * Error given type is undefined.
   *
   * @property {string} array_arg_error
   */
  const undefined_arg_error = 'Format undefined argument error.';

  /**
   * Error when given addslashes method does not exits.
   *
   * @property {string} array_arg_error
   */
  const addslashes_method_undefined = 'Format addslashes method is undefined.';

  /**
   * Defines internal format methods.
   *
   * @static
   * @property {array} $_methods
   */
  private static $_methods = array('a' => true,
                                   'd' => true,
                                   'f' => true,
                                   's' => true);

  /**
   * Length of avalaible methods.
   *
   * @static
   * @property {int} $_len
   */
  private static $_len = 4;

  /**
   * Default addslashes method.
   *
   * @static
   * @property {string} $addslashes
   */
  public static $addslashes = 'addslashes';

  /**
   * Set addslashes method.
   *
   * @static
   * @method set_addslashes
   * @param {string} $method Addslashes method
   * @return {boolean} True if succeed else false
   */
  public static function set_addslashes($method) {
    if (function_exists($method)) {
      self::$addslashes = $method;
      return (true);
    }
    return (false);
  }

  /**
   * Format given query with type parameters.
   *
   * @static
   * @method set
   * @param {...} Query and then args (optional)
   * @return {string} Query with parameters formated
   */
  public static function set() {
    $argc = func_num_args();
    if ($argc < 1) {
      return (null);
    }
    $str = func_get_arg(0);
    $query = '';
    for ($i = 1, $j = 0; isset($str{$j}) === true; ++$j) {
      if ($str{$j} == '%') {
        if ($str{$j + 1} == '%') {
          $query .= $str{++$j};
        } else if (isset(self::$_methods[$str{$j + 1}]) === true) {
          $query .= self::$str{++$j}(func_get_arg($i++));
        } else {
          $query .= $str{$j};
        }
      } else {
        $query .= $str{$j};
      }
    }
    return ($query);
  }

  /**
   * Addslashes string with given addslashes method.
   *
   * @static
   * @method addslashes
   * @param {string} $str String to addslashe
   * @return {string} String addslashes if succeed else given string
   */
  private static function addslashes($str) {
    if (function_exists(self::$addslashes) === false) {
      if (self::strict === true) {
        exit(self::addslashes_method_undefined);
      } else {
        return ($str);
      }
    }
    return (call_user_func_array(self::$addslashes, array($str)));
  }

  /**
   * Format given array.
   *
   * @static
   * @method a
   * @param {array} $arg Given array argument
   * @return {string} Given array serialized into addslashes string
   */
  private static function a(array $arg) {
    if (is_array($arg) === false) {
      exit(self::array_arg_error);
    }
    $str = '';
    $len = count($arg);
    for ($i = 0; $i < $len; ++$i) {
      $str .= '`'.self::addslashes($arg[$i]).'`';
      if (($i + 1) < $len) {
        $str .= ',';
      }
    }
    return ($str);
  }

  /**
   * Format decimal.
   *
   * @static
   * @method d
   * @param {mixed} $arg Given decimal argument
   * @return {int} Given decimal argument intval
   */
  private static function d($arg) {
    if (self::strict === true and
        is_int($arg) === false) {
      exit(self::integer_arg_error);
    }
    return (intval($arg));
  }

  /**
   * Format float.
   *
   * @static
   * @method f
   * @param {mixed} $arg Given float argument
   * @return {float} Given float argument floatval
   */
  private static function f($arg) {
    if (self::strict === true and
        is_float($arg) === false) {
      exit(self::float_arg_error);
    }
    return (floatval($arg));
  }

  /**
   * Format string. (must be between quotes [simple or double])
   *
   * @static
   * @method s
   * @param {string} $arg Given string unslashes
   * @return {string} Given string argument addslashes
   */
  private static function s($arg) {
    if (self::strict === true and
        is_string($arg) === false) {
      exit(self::string_arg_error);
    }
    return (self::addslashes($arg));
  }

  /**
   * Called when an argument does not exits.
   *
   * @static
   * @method undefined
   * @param {mixed} $arg Given argument
   */
  private static function undefined($arg) {
    if (self::strict === true) {
      exit(self::undefined_arg_error);
    }
  }
}