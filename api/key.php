<?php
require_once('../include/db_info.inc.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    
    echo json_encode($list);
}
?>
