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
   * @param {string} $database Database name
   * @return {string} Query
   */
  public function get_schemas($database='information_schema') {
    $query = Format::set('SELECT `t0`.`SCHEMA_NAME` AS `name` FROM `%s`.'.
                         '`SCHEMATA` AS `t0` ORDER BY `t0`.`SCHEMA_NAME`',
                         $database);
    return ($this->gets_assoc($query));
  }

  /**
   * Create database schema.
   *
   * @method create_schema
   * @param {string} $name Schema name
   * @param {string} $database Database name
   * @return {string} Query
   */
  public function create_schema($name, $database='information_schema') {
    $query = Format::set('CREATE DATABASE `%s`', $name);
    return ($query);
  }

  /**
   * Destroy database schema.
   *
   * @method destroy_schema
   * @param {string} $name Schema name
   * @param {string} $database Database name
   * @return {string} Query
   */
  public function destroy_schema($name, $database='information_schema') {
    $query = Format::set('DROP DATABASE `%s`', $name);
    return ($query);
  }
}