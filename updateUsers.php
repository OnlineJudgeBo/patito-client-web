<?php
require_once("./include/db_info.inc.php");
require_once("./include/login-".$OJ_LOGIN_MOD.".php");

$sql = "SELECT user_id FROM users WHERE is_deleted = 0";
$result = mysql_query($sql);

while ($row = mysql_fetch_object($result)) {
    $query = "SELECT count(*) as total FROM loginlog WHERE user_id = '".$row->user_id."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_object($result2);
    if (intval($row2->total) == 0) {
        $queryUpdate = "UPDATE users SET is_deleted = 1 WHERE user_id = '".$row->user_id."'";
	echo "<pre>";
	print_r($row->user_id." ---- ".intval($row2->total));
	echo "</pre>";
	echo "<hr>";
       mysql_query($queryUpdate);
//       exit();
    }
}

