<?php

/**
 * Table collection class.
 *
 * @class Table
 * @extends ICollection
 */
class Table extends ICollection {
  /**
   * Table constructor.
   *
   * @constructor
   * @param array $values Values
   */
  public function __construct(array $values) {
    $this->set_design(array('name',
                            'columns',
                            'database'));
    foreach ($this->get_design() as $key) {
      $this->set($key, $values);
    }
  }

  /**
   * Default name setter.
   *
   * @method _set_name
   * @param array $values Values
   * @return string Name
   */
  protected function _set_name(array $values) {
    if (empty($values['name']) === false) {
      return ($values['name']);
    }
    return ('');
  }

  /**
   * Default database setter.
   *
   * @method _set_database
   * @param Database $database Database
   * @return Database Database
   */
  protected function _set_database($database) {
    return ($database);
  }

  /**
   * Default columns setter.
   *
   * @method set_columns
   * @param array $values Values
   * @return array Values
   */
  public function set_columns(array $values) {
    foreach ($values as $key => $column) {
      $column->set('database', $this->get('database'));
      $column->set('table', $this);
      $values[$column->get('name')] = $column;
      unset($values[$key]);
    }
    return ($values);
  }

  /**
   * Return columns from table.
   *
   * @method get_columns
   * @return array Columns
   */
  public function get_columns() {
    $columns = $this->get('columns');
    if (empty($columns)) {
      $database = $this->get('database')->get('name');
      $table = $this->get('name');
      $columns = $this->set_columns($this->get_db()->get_columns($database, $table));
    }
    return ($columns);
  }

  /**
   * Select informations from table.
   *
   * @method select
   * @param int $start Start offset
   * @param int $limit Number of items
   * @param string $field Order column name
   * @param string $direction Order type (ASC, DESC)
   * @return array Results
   */
  public function select($start=0, $limit=0, $field='', $direction='ASC') {
    $results = $this->get_db()->get_data($this->get('database')->get('name'),
                                         $this->get('name'),
                                         $start, $limit, $field, $direction);
    return ($results);
  }

  /**
   * Return count rows into table.
   *
   * @method count
   * @return int Count rows
   */
  public function count() {
    return ($this->get_db()->get_count($this->get('database')->get('name'),
                                       $this->get('name')));
  }

  /**
   * Check if given column is a primary key.
   *
   * @method is_primary_key
   * @param Column $column Column
   * @return boolean True if primary key else false
   */
  public function is_primary_key($column) {
    return ($column->get('key') != 'PRI');
  }

  /**
   * Return primary key from columns.
   *
   * @method get_primary_key
   * @return Column Column
   */
  public function get_primary_key() {
    $primary = array();
    $columns = $this->get_columns();
    $column_first = null;
    foreach ($columns as $column) {
      if (empty($column_first)) {
        $column_first = $column;
      }
      if ($column->get('key') == 'PRI') {
        $primary[] = $column;
      }
    }
    if (empty($primary[0]) === false) {
      return ($primary[0]);
    }
    return ($column_first);
  }
}