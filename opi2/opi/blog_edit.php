<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Editar Blog";
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>Please LogIn First!</a>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}
	$user_id=$_SESSION['user_id'];
	$blog_id=$_GET['blog'];
	//validando blog_id
	//validar
	$sql=    "SELECT * "
			."FROM `blog` "
			."WHERE blog_id=$blog_id";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	if($user_id != $row->user_id){
	   echo "<div id='sms'>No es propietario de este blog</div>";
	   $row="";
	}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/blogpage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

