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
    return ($this->call('read_'.$type, array($node)));
  }

  /**
   * Read given database.
   *
   * @method callable_read_database
   * @param string $database Database name
   * @return array Results
   */
  public function callable_read_database($database='') {
    $nodes = array();
    $schemas = $this->get_db()->get_schemas();
    foreach ($schemas as $schema) {
      $nodes[] = array('type' => 'schema',
                       'id' => $schema['name'],
                       'text' => $schema['name'],
                       'iconCls' => 'icon-node-schema',
                       'leaf' => false);
    }
    return ($nodes);
  }
}
