<?php

require dirname(__FILE__).'/../json/parser.php';
require dirname(__FILE__).'/../json/error.php';
require dirname(__FILE__).'/../collections/core.php';
require dirname(__FILE__).'/../database/core.php';
require dirname(__FILE__).'/../modules/interface.php';

/**
 * Controller class
 *
 * @class Controller
 */
class Controller extends JsonError {
  /**
   * Raw input.
   *
   * @property string $__raw_input
   */
  private $__raw_input;

  /**
   * Module instances store.
   *
   * @property array $__modules
   */
  private $__modules;

  /**
   * Json requests store.
   *
   * @property array $__requests
   */
  private $__requests;

  /**
   * Requests results store.
   *
   * @property array $__results
   */
  private $__results;

  /**
   * Database instance.
   *
   * @property Database $__db
   */
  private $__db;

  /**
   * Controller constructor.
   *
   * @constructor
   * @param string $raw_input Raw json string input
   */
  public function __construct($raw_input='') {
    $this->__db = new Database();
    $this->__raw_input = $raw_input;
    $this->__modules = array();
    $this->__results = array();
    $this->__requests = $this->__parse_intput();
  }

  /**
   * Parse input and store each requests.
   *
   * @private
   * @method __parse_input
   * @return array Requests array
   */
  private function __parse_intput() {
    $requests = JsonParser::decode($this->__raw_input);
    if (is_array($requests) === false or
        empty($requests[0])) {
      $requests = array($requests);
    }
    return ($requests);
  }

  /**
   * Add a new request.
   *
   * @method add_request
   * @param string $request Request to add
   * @return boolean True if succeed else false
   */
  public function add_request($request='') {
    $request = JsonParser::decode($request);
    if (is_array($request) === false) {
      $request = array($request);
    }
    $this->__requests[] = $request;
    return (true);
  }

  /**
   * Execute given request.
   *
   * @method execute
   * @param array $request Request
   * @return boolean True if succeed else false
   */
  public function execute(array $request=array()) {
    if ($this->check_request($request) === false) {
      return (false);
    }
    $module = $this->get_module($request['action']);
    $result = $module->call($request['method'], $request['data']);
    return ($this->add_result($request, $result));
  }

  /**
   * Add result from request.
   *
   * @method add_result
   * @param array $request Request
   * @param mixed $result Request result
   * @return boolean True if succeed else false
   */
  public function add_result(array $request, $result) {
    $this->__results[] = array('action' => $request['action'],
                               'method' => $request['method'],
                               'tid' => $request['tid'],
                               'type' => $request['type'],
                               'result' => $result);
    return (true);
  }

  /**
   * Return all requests result.
   *
   * @method get_results
   * @return string Requests results
   */
  public function get_results() {
    return (JsonParser::encode($this->__results));
  }

  /**
   * Get module from action name.
   *
   * @method get_module
   * @param string $module_name Module name
   * @return mixed Module instance if succeed else false
   */
  public function get_module($module_name='') {
    $module_name = basename($module_name);
    if (empty($this->__modules[$module_name]) and
        $this->__include_module($module_name) === false) {
      return (false);
    }
    return ($this->__modules[$module_name]);
  }

  /**
   * Include module from module name.
   *
   * @private
   * @method __include_module
   * @param string $module_name Module name
   * @return boolean True if succeed else false
   */
  private function __include_module($module_name='') {
    $module_name = basename($module_name);
    $class_name = ucfirst($module_name).'Module';
    if (class_exists($class_name) === false) {
      $path = dirname(__FILE__).'/../modules/'.
        $module_name.'/'.$module_name.'.php';
      if (file_exists($path)) {
        require $path;
      }
    }
    if (class_exists($class_name)) {
      $this->__modules[$module_name] = new $class_name($this->__db);
      return (true);
    }
    return (false);
  }

  /**
   * Check given request.
   *
   * @method check_request
   * @param array $request Request
   * @return boolean True if request is correct else false
   */
  public function check_request(array $request=array()) {
    foreach (array('action', 'method', 'tid') as $key) {
      if (empty($request[$key])) {
        return (false);
      }
    }
    $module = $this->get_module($request['action']);
    if (empty($module)) {
      return (false);
    }
    return (true);
  }

  /**
   * Return all requests.
   *
   * @method get_requests
   * @return array Requests
   */
  public function get_requests() {
    return ($this->__requests);
  }
}
