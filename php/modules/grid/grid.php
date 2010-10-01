<?php

/**
 * GridModule class.
 *
 * @class GridModule
 * @extends IModule
 */
class GridModule extends IModule {
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
  public function callable_read($database, $table, $start, $limit, $order_column, $order_type) {
  }
}