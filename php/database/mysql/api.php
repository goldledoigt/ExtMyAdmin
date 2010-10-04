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
   * @param int $limit Limit value
   * @return array Results
   */
  public function get_data($database='information_schema', $table, $start=0, $limit=25, $order_column='', $order_type='ASC') {
    $order = false;
    if (empty($order_column) === false) {
      $order = Format::set('ORDER BY `t0`.`%s` %s ', $order_column, $order_type);
    }
    $query = Format::set('SELECT * FROM `%s`.`%s` AS `t0` '.
                         (empty($order) ? '' : $order).
                         'LIMIT %d, %d',
                         $database, $table, $start, $limit);
    return ($this->gets_assoc($query));
  }

  /**
   * Return count of last executed query.
   *
   * @method get_count
   * @param mixed False if failed else query rows number
   */
  public function get_count() {
    return ($this->get_num());
  }
}