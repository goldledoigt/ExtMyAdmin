<?php

/**
 * Column collection class.
 *
 * @class Column
 * @extends ICollection
 */
class Column extends ICollection {
  public function __construct(array $values) {
    $this->_design = array('name',
                           'type',
                           'length',
                           'charset',
                           'null',
                           'key',
                           'default',
                           'extra');
  }
}