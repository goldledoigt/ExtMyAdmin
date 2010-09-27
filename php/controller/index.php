<?php
header('Content-Type:text/javascript');

require_once '../../settings.php';

function __autoload($class_name) {
  require_once dirname(__FILE__).'/../../php/classes/'.$class_name.'.php';
}

$result = array();
$requests = json_decode(file_get_contents( 'php://input' ));

if (!is_array($requests)) $requests = array($requests);

foreach ($requests as $request) {
	$r = new request($request);
	$result[] = $r->getResult();
}

print json_encode($result);

?>
