<?php
require_once("./include/db_info.inc.php");
require_once("./include/login-".$OJ_LOGIN_MOD.".php");

$sql = "SELECT * FROM pais order by usuarios";
$data = mysql_query($sql);
$sel = " selected";
for ($i=0; $i <mysql_num_rows($data) ; $i++) { 
	$retorno .="<option value='".mysql_result($data, $i,'id_pais')."'".$sel." >".utf8_decode(mysql_result($data, $i,'nombre'))."</option>";
	$sel = "";
}
echo $retorno;

