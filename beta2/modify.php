<?php 
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Welcome To Online Judge";
require_once("./include/check_post_key.php");
require_once("./include/my_func.inc.php");


$user_id	  	= $_SESSION['user_id'];
$len		    = strlen($user_id);
$school		    = "";
$vcode		    = trim($_POST['vcode']);

$institucion    = "";

$nombre  		= trim($_POST['name']);
$lastname 		= trim($_POST['lastname']);
$pais           = trim($_POST['pais']);
$obi            = trim($_POST['obi']);
$institution_id = trim($_POST['institution_id']);
$institucion_uni= trim($_POST['institucion_uni']);
$email		    = trim($_POST['email']);

$len=strlen($nombre);

if($obi == 0){
	//si universidad
	if(!is_numeric($institucion_uni) or !is_numeric($pais)){
		die("Esto no se vale :p");
	}
	$institucion = $institucion_uni;
}else if($obi == 1){
	if(!is_numeric($institution_id) or !is_numeric($pais)){
		die("Esto no se vale :p");
	}
	$institucion = $institution_id;
}





if ($len>100){
	$err_str=$err_str."Tu Nombre es tan grande!";
	$err_cnt++;
}else if ($len==0) $nombre=$user_id;

$password=$_POST['opassword'];

$sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);

if ($row && pwCheck($password,$row['password'])) $rows_cnt = 1;
else $rows_cnt = 0;
mysql_free_result($result);
if ($rows_cnt==0){
	$err_str=$err_str."Old Password Wrong";
	$err_cnt++;
}
$len=strlen($_POST['npassword']);
if ($len<6 && $len>0){
	$err_cnt++;
	$err_str=$err_str."Password should be Longer than 6!\\n";
}else if (strcmp($_POST['npassword'],$_POST['rptpassword'])!=0){
	$err_str=$err_str."Two Passwords Not Same!";
	$err_cnt++;
}

$len=strlen($_POST['email']);
if ($len>100){
	$err_str=$err_str."Email Too Long!";
	$err_cnt++;
}
if ($err_cnt>0){
	print "<script language='javascript'>\n";
	echo "alert('";
		echo $err_str;
		print "');\n history.go(-1);\n</script>";
exit(0);

}
if (strlen($_POST['npassword'])==0) $password=pwGen($_POST['opassword']);
else $password=pwGen($_POST['npassword']);
$nombre =mysql_real_escape_string(htmlspecialchars ($nombre));
$email=mysql_real_escape_string(htmlspecialchars ($email));
$school = "_";
$sql  ="UPDATE `users` SET"
."`password`='".($password)."',"
."`nick`='".($nombre)."',"
."`school`='".($school)."',"
."`email`='".($email)."',"
."`lastname`='".($lastname)."',"
."`pais_id`='".($pais)."',"
."`obi`='".($obi)."',"
."`institucion_id`='".($institucion)."'"
." WHERE `user_id`='".mysql_real_escape_string($user_id)."'"
;
//echo $sql;
//exit(0);
mysql_query($sql);// or die("Insert Error!\n");
header("Location: ./");
?>
