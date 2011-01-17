<?php

/**
 * Database collection class.
 *
 * @class Database
 * @extends ICollection
 */
class Database extends ICollection {
  /**
   * Database constructor.
   *
   * @constructor
   * @param array $values Values. Defaults to empty array.
   */
  public function __construct(array $values=array()) {
    $this->set_design(array('name',
                            'host',
                            'tables'));
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
   * Default tables setter.
   *
   * @method set_tables
   * @param array $values Values
   * @return array Values
   */
  public function set_tables(array $values) {
    foreach ($values as $key => $table) {
      $table->set('database', $this);
      $values[$table->get('name')] = $table;
      unset($values[$key]);
    }
    return ($values);
  }

  /**
   * Return tables from database.
   *
   * @method get_tables
   * @param string $database_name Database name
   * @return array Tables
   */
  public function get_tables() {
    $tables = $this->get('tables');
    if (empty($tables)) {
      $tables = $this->set_tables($this->get_db()->get_tables($this->get('name')));
    }
    return ($tables);
  }

  /**
   * Return table from its name.
   *
   * @method get_table
   * @param string $table_name Table name
   * @return Table Table
   */
  public function get_table($table_name) {
    $tables = $this->get_tables();
    if (empty($tables[$table_name])) {
      $table = new Table(array('name' => $table_name));
      $tables = $this->set_tables($tables +
                                  array($table_name => $table));
    }
    return ($tables[$table_name]);
  }

  /**
   * Add table to current database.
   *
   * @todo
   * @method add_table
   * @param string $table_name Table name
   * @return boolean True if succeed else false
   */
  public function add_table($table_name) {
    $table = $this->get_table($table_name);
    var_dump($table);
    exit(1);
  }

  /**
   * Drop this database.
   *
   * @method drop
   * @return boolean True if succeed else false
   */
  public function drop() {
    $name = $this->get('name');
    return ($this->get_db()->drop_database($name));
  }
}
