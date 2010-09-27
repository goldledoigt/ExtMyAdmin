<?php

/**
 * ORM Interface class
 *
 * @class iorm
 */
class iorm extends json_error {
  /**
   * Database hostname
   *
   * @property {string} $_db_host
   */
  protected $_db_host;

  /**
   * Database username
   *
   * @property {string} $_db_user
   */
  protected $_db_user;

  /**
   * Database user password
   *
   * @property {string} $_db_pass
   */
  protected $_db_pass;

  /**
   * Current database
   *
   * @property {string} $_db_name
   */
  protected $_db_name;

  /**
   * ORM Interface constructor
   *
   * @construct
   * @param {string} $db_host Database hostname
   * @param {string} $db_user Database username
   * @param {string} $db_pass Database password
   * @param {string} $db_name Database name
   */
  public function __construct($db_host='', $db_user='',
                              $db_pass='', $db_name='') {
    foreach (array('db_host', 'db_user', 'db_pass', 'db_name') as $param) {
      $class_param = '_'.$param;
      if (empty($$param) and defined('settings::'.$param)) {
        $this->$class_param = constant('settings::'.$param);
      } else {
        $this->$class_param = $$param;
      }
    }
    $this->connect();
  }

  /**
   * Close current object connection to database
   *
   * @destructor
   * @method __destruct
   */
  public function __destruct() {
    $this->close();
  }
}