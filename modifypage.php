<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Bienvenido al Juez Patito";
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>Please LogIn First!</a>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}

$sql="SELECT nick,lastname,pais_id,obi,institucion_id,email FROM users WHERE user_id='".$_SESSION['user_id']."'";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
$row_institucion = "";
if($row->obi == 1){
	$sql = "SELECT * FROM colegios where id_colegio = ".$row->institucion_id;
	$result=mysql_query($sql);
	$row_institucion = mysql_fetch_object($result);
}else{
	$sql = "SELECT * FROM institucion where id_pais = ".$row->pais_id." and id_institucion = ".$row->institucion_id;
	$result=mysql_query($sql);
	$row_institucion = mysql_fetch_object($result);
}

$sql = "SELECT * FROM pais order by usuarios";
$data = mysql_query($sql);
$pais = "";
$sel = "";
for ($i=0; $i <mysql_num_rows($data) ; $i++) { 
	if(mysql_result($data, $i,'id_pais') == $row->pais_id){
		$sel = "";
	}
	$pais .="<option value='".mysql_result($data, $i,'id_pais')."'".$sel." >".utf8_decode(mysql_result($data, $i,'nombre'))."</option>";
	$sel = "";
}

mysql_free_result($result);
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/modifypage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>


