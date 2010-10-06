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
   * @param string $database Database name
   * @param string $table Table name
   * @param string $start Start offset
   * @param string $limit Number of items
   * @param string $order_column Order column name
   * @param string $order_type Order type (ASC, DESC)
   * @return array Results
   */
  public function callable_read($database, $table, $start, $limit, $order_field, $order_dir) {
    $result = array();
    $table = str_replace('table/', '', $table);
    $database = str_replace('schema/', '', $database);
    $columns = $this->get_db()->get_columns($database, $table);
    $opt = array('direction' => $order_dir,
                 'field' => $order_field);
    $primary = $this->_get_primary_key($columns);
    if (empty($opt['field'])) {
      $opt['field'] = $primary->get('name');
      $opt['direction'] = 'ASC';
    }
    $data = $this->get_db()->get_data($database, $table, $start, $limit, $opt['field'], $opt['direction']);
    $results = array('columns' => $this->_format_columns($columns),
                     'count' => $this->get_db()->get_count($database, $table),
                     'rows' => $data,
                     'metaData' => $this->_format_metadata($columns, $opt),
                     'success' => true);
    return ($results);
  }

  /**
   * Format metadata for grid.
   *
   * @method _format_metadata
   * @param array $columns Columns
   * @param array $opt Options
   * @return array Metadata
   */
  protected function _format_metadata(array $columns, array $opt=array()) {
    $metadata = array('fields' => array(),
                      'idProperty' => $this->_get_primary_key($columns)->get('name'),
                      'root' => 'rows',
                      'successProperty' => 'success',
                      'totalProperty' => 'count',
                      'sortInfo' => $opt);
    foreach ($columns as $column) {
      $metadata['fields'][] = array('name' => $column->get('name'),
                                    'type' => $column->get('type'));
    }
    return ($metadata);
  }

  /**
   * Return primary key from columns.
   *
   * @method _get_primary_key
   * @param array $columns Columns
   * @return Column Column
   */
  protected function _get_primary_key(array $columns) {
    $primary = null;
    foreach ($columns as $column) {
      if ($column->get('key') == 'PRI') {
        $primary = $column;
      }
    }
    if (empty($primary)) {
      return ($columns[0]);
    }
    return ($primary);
  }

  /**
   * Format columns for grid.
   *
   * @method _format_columns
   * @param array $columns
   * @return array Results
   */
  protected function _format_columns(array $columns) {
    $results = array();
    $primary = $this->_get_primary_key($columns);
    foreach ($columns as $column) {
      $results[] = array('align' => 'right',
                         'dataIndex' => $column->get('name'),
                         'editable' => ($column->get('name') === $primary->get('name') ? false : true),
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