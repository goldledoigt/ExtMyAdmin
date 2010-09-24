<?php
header('Content-Type:text/javascript');

require_once '../../settings.php';

function __autoload($class_name) {
	global $DOC_ROOT;
    require_once $DOC_ROOT. '/php/classes/' . $class_name . '.php';
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
