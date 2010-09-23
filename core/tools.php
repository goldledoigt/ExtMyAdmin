<?php

function cleanString($str) {
	return str_replace("\r", "", str_replace("\n", "<br />", $str));
}

function isAssoc($var) {
	return is_array($var) && array_keys($var)!==range(0,sizeof($var)-1);
}

function jsonEncode($array) {
	$is_assoc = isAssoc($array);
  	$json = $is_assoc ? '{' : '[';
  	foreach ($array as $key => $value) {
    	if (is_string($value)/* and !is_numeric($value) */and $key !== 'handler' and $key !== 'renderer' and $key !== 'fn' and $key !== 'editor')
      		$value = '"'.addslashes($value).'"';
    	if ($value === true) $value = 'true';
    	else if ($value === false) $value = 'false';
    	if (is_array($value)) $value = jsonEncode($value);
    	$array[$key] = $is_assoc ? $key.':'.$value : $value;
  	}
  	$json .= implode(',', $array);
  	$json .= $is_assoc ? '}' : ']';
  	return $json;
}

function __autoload($class_name) {
	global $DOC_ROOT;
    require_once $DOC_ROOT. '/core/' . $class_name . '.php';
}

?>