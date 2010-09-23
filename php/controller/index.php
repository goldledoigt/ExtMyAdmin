<?php
// $DOC_ROOT = $_SERVER['DOCUMENT_ROOT'].'/project/ExtMyAdmin';

require_once '../../settings.php';

function __autoload($class_name) {
	global $DOC_ROOT;
    require_once $DOC_ROOT. '/php/classes/' . $class_name . '.php';
}

header('Content-Type:text/plain');
$request = json_decode(file_get_contents( 'php://input' ));
$r = new request($request);
print $r->getJson();

/*

GRID READ: grid, read, table, [sort, dir, limit, start]

	my->getResult();
		grid->getData();
			table->select();
				select->parseQuery();
				select->exec();
		grid->getColumns();
			table->getFields()
			grid->getEditor();
		grid->getCount();

*/

?>