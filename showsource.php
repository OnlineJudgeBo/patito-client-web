<?php
$cache_time=90;
$OJ_CACHE_SHARE=false;
//require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');

if (!isset($_GET['id'])){
	$view_errors= "No such code!\n";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}

/////////////////////////Template

require("template/".$OJ_TEMPLATE."/showsource.php");

/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

