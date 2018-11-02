<?php
header("Cache-Control: no-cache, must-revalidate");// HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");// Date in the past

////////////////////////////Common head
$cache_time     = 2;
$OJ_CACHE_SHARE = false;
require_once ('./include/cache_start.php');
require_once ('./include/db_info.inc.php');

/////////////////////////Template
require ("template/".$OJ_TEMPLATE."/status.php");

/////////////////////////Common foot
if (file_exists('./include/cache_end.php')) {
	require_once ('./include/cache_end.php');
}

?>

