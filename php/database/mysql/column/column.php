<?php

/**
 * MySQL column class.
 *
 * @class MysqlColumn
 * @extends Column
 */
class MysqlColumn extends Column {
  protected $_values;

  public function __construct(array $values) {
    parent::__construct($values);
  }

  protected function _set_name() {

  }
}