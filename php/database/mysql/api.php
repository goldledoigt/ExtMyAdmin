<?php

require dirname(__FILE__).'/column/column.php';

/**
 * MySQL API
 *
 * @class MysqlApi
 * @extends idatabase
 */
abstract class MysqlApi extends IDatabase {
  /**
   * Create given database name.
   *
   * @method add_database
   * @return boolean True if succeed else false
   */
  public function add_database($database) {
    $query = Format::set('CREATE DATABASE `%s`', $database);
    $result = $this->execute($query);
    return ($result);
  }

  /**
   * Return database schemas.
   *
   * @method get_databases
   * @return array Results
   */
  public function get_databases() {
    $query = Format::set('SHOW DATABASES');
    $databases = $this->gets_row($query, array('name'));
    foreach ($databases as &$database) {
      $database = new Database($database);
    }
    return ($databases);
  }

  /**
   * Return database tables.
   *
   * @method get_tables
   * @param string $database Database name
   * @return array Results
   */
  public function get_tables($database) {
    $query = Format::set('SHOW TABLES FROM `%s`', $database);
    $tables = $this->gets_row($query, array('name'));
    foreach ($tables as &$table) {
      $table = new Table($table);
    }
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
  public function get_columns($database, $table) {
    $query = Format::set('SHOW COLUMNS FROM `%s`.`%s`',
                         $database, $table);
    $columns = $this->gets_assoc($query);
    foreach ($columns as &$column) {
      $column = new MysqlColumn($column);
    }
    return ($columns);
  }

  /**
   * Get data from database, table.
   *
   * @method get_data
   * @param string $database Database name
   * @param string $table Table name
   * @param int $start Start value
   * @param int $num Limit value
   * @return array Results
   */
  public function get_data($database, $table, $start=0, $num=0, $order_column='', $order_type='ASC') {
    $order = false;
    if (empty($order_column) === false) {
      $order = Format::set('ORDER BY `t0`.`%s` %s ', $order_column, $order_type);
    }
    $limit = false;
    if (empty($num) === false) {
      $limit = Format::set('LIMIT %d, %d', $start, $num);
    }
    $query = Format::set('SELECT * FROM `%s`.`%s` AS `t0` '.
                         (empty($order) ? '' : $order).
                         (empty($limit) ? '' : $limit),
                         $database, $table);
    return ($this->gets_assoc($query));
  }

  /**
   * Return count of last executed query.
   *
   * @method get_count
   * @param string $database Database name
   * @param string $table Table name
   * @return mixed False if failed else query rows number
   */
  public function get_count($database, $table) {
    $query = Format::set('SELECT * FROM `%s`.`%s` AS `t0` ',
                         $database, $table);
    $query = $this->execute($query);
    return ($this->get_num());
  }

  /**
   * Rename old table name to new table name.
   *
   * @method rename_table
   * @param string $database Database name
   * @param string $old_table Old table name
   * @param string $new_table New table name
   */
  public function rename_table($database, $old_table, $new_table) {
    $query = Format::set('RENAME TABLE `%s`.`%s` TO `%s`.`%s`',
                         $database, $old_table, $database, $new_table);
    $query = $this->execute($query);
    return ($query);
  }

  /**
   * Drop given database.
   *
   * @method drop_database
   * @param string $database Database name
   * @return boolean True if succeed else false
   */
  public function drop_database($database) {
    $query = Format::set('DROP DATABASE `%s`', $database);
    $query = $this->execute($query);
    return ($query);
  }

  /**
   * Drop given table.
   *
   * @method drop_table
   * @param string $table Table name
   * @return boolean True if succeed else false
   */
  public function drop_table($database, $table) {
    $query = Format::set('DROP TABLE `%s`.`%s`', $database, $table);
    $query = $this->execute($query);
    return ($query);
  }
}