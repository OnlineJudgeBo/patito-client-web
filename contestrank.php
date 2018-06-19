<?php
$OJ_CACHE_SHARE=true;
$cache_time=10;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/contestrank.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
