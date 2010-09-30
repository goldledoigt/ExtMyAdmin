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
   * @method get_schemas
   * @param string $database Database name
   * @return array Results
   */
  public function get_schemas($database='information_schema') {
    $query = Format::set('SELECT `t0`.`SCHEMA_NAME` AS `name` '.
                         'FROM `information_schema`.`SCHEMATA` AS `t0` '.
                         'ORDER BY `t0`.`SCHEMA_NAME`');
    return ($this->gets_assoc($query));
  }

  /**
   * Return database tables.
   *
   * @method get_tables
   * @param string $database Database name
   * @return array Results
   */
  public function get_tables($database='information_schema') {
    $query = Format::set('SELECT `t0`.`TABLE_NAME` AS `name` '.
                         'FROM `information_schema`.`TABLES` AS `t0` '.
                         'WHERE `t0`.`TABLE_SCHEMA` = "%s"',
                         $database);
    return ($this->gets_assoc($query));
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
    $query = Format::set('SELECT `t0`.`COLUMN_NAME` AS `name` '.
                         'FROM `information_schema`.`COLUMNS` AS `t0` '.
                         'WHERE `t0`.`TABLE_SCHEMA` = "%s" '.
                         'AND `t0`.`TABLE_NAME` = "%s" '.
                         'ORDER BY `ORDINAL_POSITION`',
                         $database, $table);
    return ($this->gets_assoc($query));
  }
}