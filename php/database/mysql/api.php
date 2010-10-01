<?php

/**
 * MySQL API
 *
 * @class MysqlApi
 * @extends idatabase
 */
abstract class MysqlApi extends IDatabase {
  /**
   * Return database schemas.
   *
   * @method get_databases
   * @param string $database Database name
   * @return array Results
   */
  public function get_databases($database='information_schema') {
    $query = Format::set('SHOW DATABASES');
    $databases = $this->gets_row($query, array('name'));
    return ($databases);
  }

  /**
   * Return database tables.
   *
   * @method get_tables
   * @param string $database Database name
   * @return array Results
   */
  public function get_tables($database='information_schema') {
    $query = Format::set('SHOW TABLES FROM `%s`', $database);
    $tables = $this->gets_row($query, array('name'));
    return ($tables);
  }

  /**
   * Return table columns.
   *
   * @method get_columns
   * @param string $database Database name
   * @param string $table Table name
   * @return array Results
   */
  public function get_columns($database='information_schema', $table) {
    $query = Format::set('SHOW COLUMNS FROM `%s`.`%s`',
                         $database, $table);
    $columns = $this->gets_assoc($query);
    return ($columns);
  }
}