<?php

class request {

	public $instance;
	public $params;
	public $error = array();

	function __construct($request=false) {
		if (
			isset($request)
			and isset($request->action) and strlen($request->action)
			and isset($request->method) and strlen($request->method)
			and isset($request->data) and is_array($request->data)
			and isset($request->tid) and is_numeric($request->tid)
			and isset($request->type) and strlen($request->type)
		) {
			$this->params = $request;
		} else {
			$this->error("bad arguments");
		}
	}

	function getJson() {

		$json = array();
		$json['action'] = $this->params->action;
		$json['method'] = $this->params->method;
		$json['tid'] = $this->params->tid;
		$json['type'] = $this->params->type;
		if ($this->error) {
			$json['msg'] = $this->error['msg'];
		} else {
			// $instance = new $this->params->action($this->params->data[0]);
			// 			$json['result'] = $instance->{$this->params->method}();
			$instance = new grid();
			$json['result'] = $instance->read();
		}
		return json_encode($json);
	}

	function error($msg) {
		$this->error['msg'] = $msg;
	}

}
/*
class table { function getData() {return array();} }

$_REQUEST = array('action' => 'table', 'method' => 'getData', 'data' => '{toto:42}', 'tid' => 2, 'type' => 'rpc');

header('Content-Type:text/plain');
$r = new request($_REQUEST);
print $r->getJson();
*/
?>