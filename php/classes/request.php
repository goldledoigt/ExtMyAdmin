<?php

/**
 * Request dispatcher class
 *
 * @class request
 */
class request {
  /**
   * Current php class instance
   *
   * @var {mixed} $instance
   */
  public $instance;

  /**
   * Request parameters
   *
   * @var {array} $params
   */
  public $params;

  /**
   * Errors store
   *
   * @var {array} $error
   */
  public $error = array();

  /**
   * new request()
   *
   * @constructor
   * @method __construct
   * @param {array} $request Request array parameters
   */
  public function __construct(array $request=array()) {
    if ($this->__checkParameters($request)) {
      $this->params = $request;
    } else {
      $this->error("bad arguments");
    }
  }

  /**
   * Check request parameters
   *
   * @method __checkParameters
   * @param {array} $request Request
   * @return {boolean} True if request is correct else false
   */
  private function __checkParameters(array $request=array()) {
    if (empty($request) or
        is_array($request['data']) === false or
        is_numeric($request['tid']) === false) {
      return (false);
    }
    foreach (array('action', 'method', 'data', 'tid', 'type') as $key) {
      if (empty($request[$key])) {
        return (false);
      }
    }
    return (true);
  }

  public function getResult() {
    $json = array();
    foreach (array('action', 'method', 'tid', 'type') as $key) {
      $json[$key] = $this->params[$key];
    }
    if (empty($this->error['msg']) === false) {
      $json['msg'] = $this->error['msg'];
    } else {
      $instance = new $this->params['action']($this->params['data']);
      $json['result'] = $instance->{$this->params['method']}();
    }
    return ($json);
  }

  /**
   * Set error(s) to result array
   *
   * @method error
   * @param {string} $msg Error message
   */
  public function error($msg) {
    $this->error['msg'] = $msg;
  }
}
