<?php
header('Content-Type:text/plain');

require_once '../settings.php';
require_once '../core/tools.php';

$R =& $_REQUEST;
$json = array();
$json['success'] = false;
$json['data'] = array();

if (isset($R['type']) and strlen($R['type'])) {

	if ($R['type'] === 'schema') {
		$db_name = $R['name'];
	}
	
	$db = new mysql();
	$scope = new $R['type']($db);

	if (isset($R['cmd']) and strlen($R['cmd'])) {
		$json = $scope->$R['cmd'](json_decode($R['params']));
	} else {
		if ($R['ui'] === 'tree') {
			$json = $scope->read();
		} else if ($R['ui'] == 'grid') {
			$json['data'] = $scope->read();
			$json['success'] = true;
		}
	}

}

print jsonEncode($json);

?>