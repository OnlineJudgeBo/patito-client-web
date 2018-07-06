<?php
require_once("./include/db_info.inc.php");
require_once("./initPHP.php");

function pwGen($password,$md5ed=False) 
{
	if (!$md5ed) $password=md5($password);
	$salt = sha1(rand());
	$salt = substr($salt, 0, 4);
	$hash = base64_encode( sha1($password . $salt, true) . $salt ); 
	return $hash; 
}
//require_once("./include/my_func.inc.php");
//include('./include/recaptcha.php');

$err_str="";
$err_cnt=0;

$user_id	    = trim($_POST['user_id']);
$len		    = strlen($user_id);
$email		    = trim($_POST['email']);
$school		    = "";
//$vcode		    = trim($_POST['vcode']);

$pais           = trim($_POST['pais']);
//$obi            = trim($_POST['obi']);
//$institution_id = trim($_POST['institution_id']);
//$institucion_uni= trim($_POST['institucion_uni']);
$school = trim($_POST['school']); // OG
$anws	     	= false;
$institucion    = 0;
/*if(empty($obi)){
	//si universidad
	if(!is_numeric($institucion_uni) or !is_numeric($pais)){
		die("Pais, Universidad requeridos");
	}
	$institucion = $institucion_uni;
}else if($obi == 1){
	if(!is_numeric($institution_id) or !is_numeric($pais)){
		die("Pais, Institucion requeridos");
	}
	$institucion = $institution_id;
    }*/

$ip =  $_SERVER['REMOTE_ADDR'];
$v_ip =explode(".",$ip);

if(($v_ip[0]=='200' && $v_ip[1]=='7')) $OJ_VCODE=0;


/*if($OJ_VCODE){

    $resp = verificaCaptcha($_POST['g-recaptcha-response'],$privatekey);
    if (!$resp) {
        die ("El CAPTCHA no es correcto. Intenta de nuevo :D.");
    } else {
        $anws=true;
    }

    if (!$anws) {
        $err_str=$err_str."Seguro q eres humano ? Revisa el CAPTCHA. \\n";
        $err_cnt++;
    }
    }*/
if($OJ_LOGIN_MOD!="hustoj"){
	$err_str=$err_str."No hay registros.\\n";
	$err_cnt++;
}

if($len>20){
	$err_str=$err_str."User ID demasiado largo!\\n";
	$err_cnt++;
}else if ($len<3){
	$err_str=$err_str."User ID muy corto!\\n";
	$err_cnt++;
}
if (!is_valid_user_name($user_id)){
	$err_str=$err_str."User ID solo contiene Numeros & Letras!\\n";
	$err_cnt++;
}
$nombre=trim($_POST['name']);

if (strcmp($_POST['password'],$_POST['rptpassword'])!=0){
	$err_str=$err_str."Password no son iguales!\\n";
	$err_cnt++;
}

$len=strlen($_POST['email']);
if ($len>100){
	$err_str=$err_str."Correo no valido!\\n";
	$err_cnt++;
}
$lastname=trim($_POST['lastname']);
$len=strlen($lastname);
if($len > 15){
	$err_str=$err_str."Apellido demasiado largo!\\n";
	$err_cnt++;
}

/*$cities_id = mysql_real_escape_string($_POST['city']);
$sql = "SELECT * FROM cities where cities_id = '".$cities_id."'";
$result = mysql_query($sql);
if(mysql_num_rows(result) > 0){
	print "<script language='javascript'>\n";
	print "alert('Algo va mal en tu ciudad \\n');\n";
	print "history.go(-1);\n</script>";
	exit(0);
    }*/

/*$institute_id = mysql_real_escape_string($_POST['institute']);
$sql = "SELECT * FROM institute_type where institute_id = '".$institute_id."'";
$result = mysql_query($sql);
if(mysql_num_rows(result) > 0){
	print "<script language='javascript'>\n";
	print "alert('Te falta un campo \\n');\n";
	print "history.go(-1);\n</script>";
	exit(0);
    }*/

if ($err_cnt>0){
	print "<script language='javascript'>\n";
	print "alert('";
    print $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);

}
$password=pwGen($_POST['password']);
$sql="SELECT user_id,email FROM users WHERE user_id = '$user_id' or email = '$email'";

$result=mysql_query($sql);
$rows_cnt=mysql_num_rows($result);
mysql_free_result($result);
if ($rows_cnt == 1){
	print "<script language='javascript'>\n";
	print "alert('Ya existe el Usuario o Correo!\\n');\n";
	print "history.go(-1);\n</script>";
	exit(0);
}
$nombre  =mysql_real_escape_string(htmlspecialchars ($nombre));
$school  ="-";
$email   =mysql_real_escape_string(htmlspecialchars ($email));
$ip    =$_SERVER['REMOTE_ADDR'];

$sql="INSERT INTO `users`("
	."user_id,email,ip,accesstime,password,reg_time,nick,school,lastname,pais_id)"
    ."VALUES('".$user_id
	."','".$email."','"
	.$_SERVER['REMOTE_ADDR']
	."',NOW(),'".$password
	."',NOW(),'".$nombre
	."','".$school
	."','".$lastname."','".$pais."')";
mysql_query($sql);
$sql="INSERT INTO `loginlog` VALUES('$user_id','$password','$ip',NOW())";
mysql_query($sql);
$_SESSION['user_id']=$user_id;

$sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$_SESSION['user_id']."'";
//echo $sql."<br />";
$result=mysql_query($sql);
echo mysql_error();
while ($row=mysql_fetch_assoc($result)){
	$_SESSION[$row['rightstr']]=true;
    //echo $_SESSION[$row['rightstr']]."<br />";
}
$_SESSION['ac']=Array();
$_SESSION['sub']=Array();
?>
<script>history.go(-2);</script>
