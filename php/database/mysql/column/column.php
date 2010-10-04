<?php

/**
 * MySQL column class.
 *
 * @class MysqlColumn
 * @extends Column
 */
class MysqlColumn extends Column {
  /**
   * Set name value.
   *
   * @method _set_name
   * @param array $values Values
   * @return string Value
   */
  protected function _set_name(array $values) {
    return ($values['Field']);
  }

  /**
   * Set type value.
   *
   * @method _set_type
   * @param array $values Values
   * @return string Value
   */
  protected function _set_type(array $values) {
    $type = $values['Type'];
    if (strpos($type, '(')) {
      $type = substr($type, 0, strpos($type, '('));
    } else if (strpos($type, ' ')) {
      $type = substr($type, 0, strpos($type, ' '));
    }
    return (strtolower($type));
  }

  /**
   * Set length value.
   *
   * @method _set_length
   * @param array $values Values
   * @return int Value
   */
  protected function _set_length(array $values) {
    $length = 0;
    if (strpos($values['Type'], '(')) {
      $length = intval(substr($values['Type'],
                              strpos($values['Type'], '(') + 1,
                              strpos($values['Type'], ')')));
    }
    return ($length);
  }

  /**
   * Set unsigned value.
   *
   * @method _set_unsigned
   * @param array $values Values
   * @return boolean Value
   */
  protected function _set_unsigned(array $values) {
    return (stristr($values['Type'], 'unsigned') !== false);
  }

  /**
   * Set null value.
   *
   * @method _set_null
   * @param array $values Values
   * @return boolean Value
   */
  protected function _set_null(array $values) {
    return ($values['Null'] === 'YES');
  }

  /**
   * Set default value.
   *
   * @method _set_default
   * @param array $values Values
   * @return string Value
   */
  protected function _set_default(array $values) {
    return ($values['Default']);
  }

  /**
   * Set key value.
   *
   * @method _set_key
   * @param array $values Values
   * @return string Value
   */
  protected function _set_key(array $values) {
    return ($values['Key']);
  }

  /**
   * Set extra value.
   *
   * @method _set_extra
   * @param array $values Values
   * @return string Value
   */
  protected function _set_extra(array $values) {
    return ($values['Extra']);
  }
}