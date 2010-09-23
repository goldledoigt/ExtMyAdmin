<?php
header('Content-Type:text/plain');

require_once '../../settings.php';
require_once '../../core/tools.php';

$R =& $_REQUEST;
$json = array();
// $json['success'] = false;
// $json['data'] = array();

if (isset($R['schema']) and strlen($R['schema'])) {

	$db_name = $R['schema'];
	$db = new mysql();

	if (isset($R['table']) and strlen($R['table'])) {		
		$table = new table($db);
		$table->name = $R['table'];
		$json = $table->execQuery('SELECT * FROM '.$R['table'].' LIMIT '.$R['start'].', '.$R['limit']);
	} else if (isset($R['query']) and strlen($R['query'])) {
		$table = new table($db);
		$json = $table->execQuery($R['query']);
	}

}

// print utf8_encode(jsonEncode($json));
print jsonEncode($json);
?>