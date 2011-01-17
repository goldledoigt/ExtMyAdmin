<?php

/**
 * Module interface.
 *
 * @class IModule
 */
abstract class IModule extends JsonError {
  /**
   * @var object $_controller Controller (parent).
   */
  protected $_controller;

  /**
   * @var array $_events Events.
   */
  protected $_events;

  /**
   * Module interface constructor.
   *
   * @constructor
   * @method __construct
   * @param object $controller Controller (parent).
   */
  public function __construct($controller) {
    $this->_controller = $controller;
    $this->_events = array();
  }

  /**
   * Call module method.
   *
   * @method call
   * @param string $method Method name
   * @param array $args Arguments
   * @return mixed Error if method does not exists (or not callable),
   * else method result.
   */
  public function call($method, array $args) {
    $method = 'callable_'.basename($method);
    if (method_exists($this, $method)) {
      return (call_user_func_array(array($this, $method), $args));
    }
    return ($this->get_error('This action is not avalaible.'));
  }

  /**
   * Format and return error msg.
   *
   * @method error
   * @param string $msg Error message.
   * @return array JSON formated error message.
   */
  public function error($msg) {
    return (array('success' => false,
                  'msg' => $msg));
  }

  /**
   * Add event and return empty quotes.
   *
   * @param string $msg Error message.
   * @param string $return (optional) Return value. Defaults to empty string.
   * @return string $return variable.
   */
  public function error_event($msg, $return=array()) {
    $this->event($msg, 'event', 'exception');
    return ($return);
  }

  /**
   * Add event message to controller.
   *
   * @method event
   * @param string $msg Message.
   * @param string $type (optional) Type. Defaults to "event".
   * @param string $name (optional) Name. Defaults to "message".
   * @return array Event array description.
   */
  public function event($msg, $type='event', $name='message') {
    $this->_events[] =
      array('data' => $msg,
            'type' => $type,
            'name' => $name);
    return (true);
  }

  /**
   * Return module events.
   *
   * @method get_events
   * @return array Events (empty array if there is no event).
   */
  public function get_events() {
    return ($this->_events);
  }
}
