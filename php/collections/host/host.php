<?php

/**
 * Host collection class.
 *
 * @class Host
 * @extends ICollection
 */
class Host extends ICollection {
  /**
   * Host constructor.
   *
   * @constructor
   * @param array $values Values
   */
  public function __construct(array $values=array()) {
    $this->set_design(array('name',
                            'port',
                            'databases'));
    foreach ($this->get_design() as $key) {
      $this->set($key, $values);
    }
  }

  /**
   * Default databases setter.
   *
   * @method set_databases
   * @param array $values Values
   * @return array Values
   */
  public function set_databases(array $values) {
    foreach ($values as $key => $database) {
      $values[$database->get('name')] = $database;
      unset($values[$key]);
    }
    return ($values);
  }

  /**
   * Fetch all databases from this host.
   *
   * @method get_databases
   * @return array Databases
   */
  public function get_databases() {
    $databases = $this->get('databases');
    if (empty($databases)) {
      $databases = $this->set_databases($this->get_db()->get_databases());
    }
    return ($databases);
  }

  /**
   * Return database from cache or fetch it.
   *
   * @method get_database
   * @param string $database_name Database name
   * @return Database Database
   */
  public function get_database($database_name) {
    if ($this->has_database($database_name) === true) {
      $databases = $this->get_databases();
      return ($databases[$database_name]);
    }
    return (false);
  }

  /**
   * Check if database exists.
   *
   * @method has_database
   * @param string $database_name Database name
   * @return boolean True if database exists else false.
   */
  public function has_database($database_name) {
    $databases = $this->get_databases();
    return (!empty($databases[$database_name]));
  }

  /**
   * Add database to current host.
   *
   * @method add_database
   * @param string $database_name Database name
   * @return boolean True if succeed else false
   */
  public function add_database($database_name) {
    $database = $this->has_database($database_name);
    if ($database === false) {
      return ($this->get_db()->add_database($database_name));
    }
    return (false);
  }
}
