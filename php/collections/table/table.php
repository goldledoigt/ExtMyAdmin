<?php

/**
 * Table collection class.
 *
 * @class Table
 * @extends ICollection
 */
abstract class Table extends ICollection {
  /**
   * Table constructor.
   *
   * @constructor
   * @param array $values Values
   */
  public function __construct(array $values) {
    $this->set_design(array('name',
                            'charset',
                            'columns'));
    foreach ($this->get_design() as $key) {
      $this->set($key, $values);
    }
  }
}