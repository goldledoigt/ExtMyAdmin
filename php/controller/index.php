<?php

header('Content-Type: text/javascript');

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL | E_ERROR | E_USER_ERROR);

require dirname(__FILE__).'/../../settings.php';
require dirname(__FILE__).'/../json/error.php';
require dirname(__FILE__).'/../orm/orm.php';

/**
 * Autoload class function declaration.
 * Automaticly require file class if class does not exist.
 *
 * @function __autoload
 * @param {string} $class_name Class name
 */
function __autoload($class_name) {
  $file_path = dirname(__FILE__).'/../../php/classes/'.basename($class_name).'.php';
  if (class_exists($class_name) === false and
      file_exists($file_path) === true) {
    require $file_path;
  }
}

$result = array();
$requests = json_decode(file_get_contents('php://input'), true);
if (empty($requests['action']) === false) {
  $requests = array($requests);
}
foreach ($requests as $request) {
  $r = new request($request);
  $result[] = $r->getResult();
}

print json_encode($result);
