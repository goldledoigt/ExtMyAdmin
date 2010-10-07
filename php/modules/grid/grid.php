<?php

/**
 * GridModule class.
 *
 * @class GridModule
 * @extends IModule
 */
class GridModule extends IModule {
  /**
   * Update a field from database/table.
   *
   * @method callable_update
   * @param array $infos Infos
   */
  public function callable_update(array $infos) {
  }

  /**
   * Read action method.
   *
   * @method callable_read
   * @param string $database_name Database name
   * @param string $table_name Table name
   * @param string $start Start offset
   * @param string $limit Number of items
   * @param string $order_column Order column name
   * @param string $order_type Order type (ASC, DESC)
   * @return array Results
   */
  public function callable_read($database_name, $table_name, $start, $limit, $order_field, $order_dir) {
    $result = array();
    $host = new Host();
    $table = $host->get_database($database_name)->get_table($table_name);
    $columns = $table->get_columns();
    $opt = array('direction' => $order_dir,
                 'field' => $order_field);
    $primary = $table->get_primary_key();
    if (empty($opt['field'])) {
      $opt['field'] = $primary->get('name');
      $opt['direction'] = 'ASC';
    }
    $data = $table->select($start, $limit, $opt['field'], $opt['direction']);
    $results = array('columns' => $this->_format_columns($table),
                     'count' => $table->count(),
                     'rows' => $data,
                     'metaData' => $this->_format_metadata($table, $opt),
                     'success' => true);
    return ($results);
  }

  /**
   * Format metadata for grid.
   *
   * @method _format_metadata
   * @param Table $table Table
   * @param array $opt Options
   * @return array Metadata
   */
  protected function _format_metadata($table, array $opt=array()) {
    $metadata = array('fields' => array(),
                      'idProperty' => '',//$table->get_primary_key()->get('name'),
                      'root' => 'rows',
                      'successProperty' => 'success',
                      'totalProperty' => 'count',
                      'sortInfo' => $opt);
    foreach ($table->get_columns() as $column) {
      $metadata['fields'][] = array('name' => $column->get('name'),
                                    'type' => $column->get('type'));
    }
    return ($metadata);
  }

  /**
   * Format columns for grid.
   *
   * @method _format_columns
   * @param Table $table Table
   * @return array Results
   */
  protected function _format_columns($table) {
    $results = array();
    foreach ($table->get_columns() as $column) {
      $results[] = array('align' => 'right',
                         'dataIndex' => $column->get('name'),
                         'editable' => $table->is_primary_key($column),
                         'editor' => array('xtype' => $column->get_xtype()),
                         'format' => '0',
                         'header' => $column->get_header(),
                         'sortable' => true,
                         'tooltip' => $column->get_tooltip(),
                         'width' => $column->get_width(),
                         'xtype' => $column->get_columntype());
    }
    return ($results);
  }
}