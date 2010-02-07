<?php
$start = microtime(true);

require_once 'configure.php';
require_once 'lib/Nooges.php';

/* Run the application. */
try {
	Nooges::setConfigDb($config_db);
	Nooges::setConfigRouter($config_router);
	Nooges::init();
	Nooges::run();
} catch ( Exception $e ) {
	exit($e);
}

$end = microtime(true);
//exit($end - $start);
exit;