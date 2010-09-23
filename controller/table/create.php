<?php
header('Content-Type:text/plain');

require_once '../../settings.php';
require_once '../../core/tools.php';

$R =& $_REQUEST;
$json = array();

if (isset($R['schema']) and strlen($R['schema']) and isset($R['table']) and strlen($R['table'])) {
	$db_name = $R['schema'];
	$db = new mysql();
	$table = new table($db);
	$table->name = $R['table'];
	$json = $table->create();
}

print jsonEncode($json);
?>