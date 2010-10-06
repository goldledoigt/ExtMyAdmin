<?php

mb_internal_encoding('UTF-8');
header('Content-Type: application/json');

require dirname(__FILE__).'/../../settings.php';
require dirname(__FILE__).'/core.php';

$input = file_get_contents('php://input');
$controller = new Controller($input);
foreach ($controller->get_requests() as $request) {
  $controller->execute($request);
}
echo $controller->get_results();
