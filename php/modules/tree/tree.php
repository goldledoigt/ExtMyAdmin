<?php

/**
 * TreeModule class.
 *
 * @class TreeModule
 * @extends IModule
 */
class TreeModule extends IModule {
  /**
   * Destroy given database.
   *
   * @method callable_destroy
   * @param string $name Node name.
   * @param string $type Node type.
   * @param string $parent Parent node name.
   * @return boolean True if succeed else false
   */
  public function callable_destroy($name='', $type='', $parent='') {
    return ($this->call('destroy_'.$type, array($name, $parent)));
  }

  /**
   * Destroy given database.
   *
   * @method callable_destroy_database
   * @param string $database Database name.
   * @param string $host Host name.
   * @return boolean True if succeed else false.
   */
  public function callable_destroy_database($database='', $host='') {
    $host = new Host();
    $db = $host->get_database($database);
    if (empty($db)) {
      return ($this->error_event('Database does not exist'));
    }
    return ($db->drop());
  }

  /**
   * Destroy given table from database.
   *
   * @todo
   * @method callable_destroy_table
   * @param string $table Table name.
   * @param string $database Database name.
   * @return boolean True if succeed else false.
   */
  public function callable_destroy_table($table='', $database='') {
    if (empty($table)) {
      return ($this->error_event('No table given.'));
    }
    $host = new Host();
    $db = $host->get_database($database);
    if (empty($db)) {
      return ($this->error_event('Database does not exist'));
    }
    $table = explode('/', $table);
    $table = $table[count($table) - 1];
    $tb = $db->get_table($table);
    if (empty($tb)) {
      return ($this->error_event('Table does not exists'));
    }
    $result = $tb->drop();
    if ($result) {
      $this->event('Table dropped successfully.');
      return ($result);
    }
    return ($this->error_event('Failed to drop table.', $result));
  }

  /**
   * Create database/table into given host/database.
   *
   * @method callable_create
   * @param string $name New node name.
   * @param string $type Node type.
   * @param string $parent Parent node name.
   * @return boolean True if succeed else false
   */
  public function callable_create($name='', $type='', $parent='') {
    return ($this->call('create_'.$type, array($name, $parent)));
  }

  /**
   * Create new database into given host.
   *
   * @method callable_create_database
   * @param string $database Database name.
   * @param string $host Host name.
   * @return boolean True if succeed else false.
   */
  public function callable_create_database($database='', $host='') {
    $host = new Host();
    $result = $host->add_database($database);
    if ($result == true) {
      $d = $host->get_database($database);
      return ($this->_format_result($d, 'database'));
    }
    return ($this->error_event('Failed to add database.'));
  }

  /**
   * Create new table into given database.
   *
   * @method callable_create_table
   * @param string $table Table name.
   * @param string $database Database name.
   * @return boolean True if succeed else false.
   */
  public function callable_create_table($table, $database) {
    $host = new Host();
    $db = $host->get_database($database);
    $result = $db->add_table($table);
    if ($result == true) {
      $t = $db->get_table($table);
      return ($this->_format_result($t, 'table', $database.'/'));
    }
    return ($this->error_event('Failed to add table.'));
  }

  /**
   * Update table name.
   *
   * @method callable_update
   * @param string $old_table_name Old table name
   * @param string $database_name Database name
   * @param string $new_table_name New table name
   * @return boolean True if succeed else false
   */
  public function callable_update($old_table_name='', $database_name='', $new_table_name='') {
    $host = new Host();
    $db = $host->get_database($database_name);
    if (empty($db)) {
      return ($this->error_event('Given database does not exists'));
    }
    $tb = $db->get_table($old_table_name);
    if (empty($tb)) {
      return ($this->error_event('Given table does not exists'));
    }
    $result = $tb->rename($new_table_name);
    if ($result === true) {
      $this->event('Success to rename table `'.$old_table_name.'` to `'.
                   $new_table_name.'`');
      return (array('success' => true,
                    'id' => $database_name.'/'.$new_table_name,
                    'text' => $new_table_name));
    }
    return ($this->error_event('Can\'t rename this table.'));
  }

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
   * Read databases from given host.
   *
   * @method callable_read_host
   * @param string $host Host name
   * @param string $schema Schema name
   * @param boolean $new New flag
   * @return array Results
   */
  public function callable_read_host($host='', $schema='', $new=false) {
    $nodes = array();
    $host = new Host();
    $databases = $host->get_databases();
    foreach ($databases as $database) {
      $nodes[] = $this->_format_result($database, 'database');
    }
    return ($nodes);
  }

  /**
   * Read tables from given database.
   *
   * @method callable_read_database
   * @param string $database Database name
   * @param string $schema Schema name
   * @param boolean $new New flag
   * @return array Results
   */
  public function callable_read_database($database='', $schema='', $new=false) {
    $nodes = array();
    $host = new Host();
    $db = $host->get_database($database);
    if (empty($db)) {
      return (array());
    }
    $tables = $db->get_tables();
    foreach ($tables as $table) {
      $nodes[] = $this->_format_result($table, 'table', $database.'/');
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
    $table = substr($table, strpos($table, '/') + 1);
    $nodes = array();
    $host = new Host();
    $columns = $host->get_database($database)
      ->get_table($table)
      ->get_columns();
    foreach ($columns as $column) {
      $nodes[] = $this->_format_result($column, 'column', $database.'/'.$table.'/', true);
    }
    return ($nodes);
  }

  /**
   * Return result formated.
   *
   * @method _format_result
   * @param mixed $results Result
   * @param string $type Type of result
   * @param string $prefix Node id prefix
   * @param boolean $leaf Leaf parameter
   * @return array Result formated
   */
  protected function _format_result($result, $type, $prefix='', $leaf=false) {
    return (array('type' => $type,
                  'id' => $prefix.$result->get('name'),
                  'text' => $result->get('name'),
                  'iconCls' => 'icon-node-'.$type,
                  'leaf' => $leaf));
  }
}
