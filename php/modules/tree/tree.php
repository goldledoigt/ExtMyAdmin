<?php

/**
 * TreeModule class.
 *
 * @class TreeModule
 * @extends IModule
 */
class TreeModule extends IModule {
  /**
   * Read action method.
   *
   * @method callable_read
   * @param string $node Node
   * @param string $type Type
   * @param string $schema Schema
   * @param boolean $new New
   * @return array Results formatted for TreePanel
   */
  public function callable_read($node='', $type='', $schema='', $new=false) {
    return ($this->call('read_'.$type, array($node, $schema, $new)));
  }

  /**
   * Read databases.
   *
   * @method callable_read_database
   * @param string $database Database name
   * @param string $schema Schema name
   * @param boolean $new New flag
   * @return array Results
   */
  public function callable_read_database($database='', $schema='', $new=false) {
    $nodes = array();
    $databases = $this->get_db()->get_databases($database);
    foreach ($databases as $database) {
      $nodes[] = $this->_format_result($database, 'schema');
    }
    return ($nodes);
  }

  /**
   * Read schema from given database.
   *
   * @method callable_read_schema
   * @param string $database Database name
   * @param string $schema Schema name
   * @param boolean $new New flag
   * @return array Results
   */
  public function callable_read_schema($database='', $schema='', $new=false) {
    $nodes = array();
    $database = str_replace('schema/', '', $database);
    $tables = $this->get_db()->get_tables($database);
    foreach ($tables as $table) {
      $nodes[] = $this->_format_result($table, 'table');
    }
    return ($nodes);
  }

  /**
   * Read columns from given table.
   *
   * @method callable_read_table
   * @param string $table Table name
   * @param string $database Database name
   * @param boolean $new New flag
   * @return array Results
   */
  public function callable_read_table($table='', $database='', $new=false) {
    $nodes = array();
    $table = str_replace('table/', '', $table);
    $database = str_replace('schema/', '', $database);
    $columns = $this->get_db()->get_columns($database, $table);
    foreach ($columns as $column) {
      $nodes[] = $this->_format_result($column->gets(), 'column', true);
    }
    return ($nodes);
  }

  /**
   * Return result formated.
   *
   * @method _format_result
   * @param array $results Result
   * @param string $type Type of result
   * @param boolean $leaf Leaf parameter
   * @return array Result formated
   */
  protected function _format_result(array $result, $type, $leaf=false) {
    return (array('type' => $type,
                  'id' => $type.'/'.$result['name'],
                  'text' => $result['name'],
                  'iconCls' => 'icon-node-'.$type,
                  'leaf' => $leaf));
  }
}
