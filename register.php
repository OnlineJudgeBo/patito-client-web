<?php
require_once("./include/db_info.inc.php");
<<<<<<< HEAD
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
=======
require_once("./include/my_func.inc.php");
>>>>>>> master

$err_str = "";
$err_cnt = 0;

$user_id	    = trim($_POST['user_id']);
$len		    = strlen($user_id);
$email		    = trim($_POST['email']);
$school		    = "";
//$vcode		    = trim($_POST['vcode']);

$pais           = trim($_POST['pais']);
<<<<<<< HEAD
//$obi            = trim($_POST['obi']);
//$institution_id = trim($_POST['institution_id']);
//$institucion_uni= trim($_POST['institucion_uni']);
$school = trim($_POST['school']); // OG
$anws	     	= false;
$institucion    = 0;
/*if(empty($obi)){
	//si universidad
=======
$obi            = trim($_POST['obi']);
$institution    = trim($_POST['institution']);
$institucion_uni= trim($_POST['institucion_uni']);
$anws	     	= false;
$institucion    = 0;
$school         = "";


if(empty($obi)){
	//Universidad
>>>>>>> master
	if(!is_numeric($institucion_uni) or !is_numeric($pais)){
		die("Pais, Universidad requeridos");
	}
	$institucion = intval($institucion_uni);
}else if($obi == 1){
	if(empty($institution) or !is_numeric($pais)){
		die("Pais, Institucion requeridos");
	}
<<<<<<< HEAD
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
=======
	$school      = mysql_real_escape_string(htmlspecialchars ($institution));
	$institucion = 0;
}

>>>>>>> master
if($OJ_LOGIN_MOD!="hustoj"){
	$err_str=$err_str."No hay registros.\\n";
	$err_cnt++;
}

if($len > 20){
	$err_str=$err_str."User ID demasiado largo!\\n";
	$err_cnt++;
}else if ($len < 3){
	$err_str=$err_str."User ID muy corto!\\n";
	$err_cnt++;
}
if (!is_valid_user_name($user_id)){
	$err_str=$err_str."User ID solo contiene Numeros & Letras!\\n";
	$err_cnt++;
}
<<<<<<< HEAD
$nombre=trim($_POST['name']);

=======
$nombre = trim($_POST['name']);
$len    = strlen($nombre);

if ($len > 100){
	$err_str=$err_str."Nombre demasiado largo!\\n";
	$err_cnt++;
}else if ($len == 0){
	$err_str=$err_str."Ingrese su nombre!\\n";
	$err_cnt++;
} 
>>>>>>> master
if (strcmp($_POST['password'],$_POST['rptpassword'])!=0){
	$err_str=$err_str."Password no son iguales!\\n";
	$err_cnt++;
}
<<<<<<< HEAD

=======
if (strlen($_POST['password'])<6){
	$err_cnt++;
	$err_str=$err_str."Password > 6 caracteres!\\n";
}
$len=strlen($_POST['school']);
if ($len > 100){
	$err_str=$err_str."Nombre no valido!\\n";
	$err_cnt++;
}
>>>>>>> master
$len=strlen($_POST['email']);
if ($len > 100){
	$err_str=$err_str."Correo no valido!\\n";
	$err_cnt++;
}
$lastname=trim($_POST['lastname']);
$len=strlen($lastname);
if($len > 30){
	$err_str=$err_str."Apellido demasiado largo!\\n";
	$err_cnt++;
}

<<<<<<< HEAD
/*$cities_id = mysql_real_escape_string($_POST['city']);
$sql = "SELECT * FROM cities where cities_id = '".$cities_id."'";
$result = mysql_query($sql);
if(mysql_num_rows(result) > 0){
=======
if ($err_cnt > 0){
>>>>>>> master
	print "<script language='javascript'>\n";
	print "alert('";
	print $err_str;
	print "');\n history.go(-1);\n</script>";
	exit(0);
    }*/

<<<<<<< HEAD
/*$institute_id = mysql_real_escape_string($_POST['institute']);
$sql = "SELECT * FROM institute_type where institute_id = '".$institute_id."'";
$result = mysql_query($sql);
if(mysql_num_rows(result) > 0){
=======
$password = pwGen($_POST['password']);
$sql      = "SELECT user_id,email FROM users WHERE user_id = '$user_id'";
$result   = mysql_query($sql);

$rows_cnt = mysql_num_rows($result);
mysql_free_result($result);
if ($rows_cnt == 1){
>>>>>>> master
	print "<script language='javascript'>\n";
	print "alert('EL Usuario ya existe, intenta con otro nombre de Usuario o recupera tu cuenta\\n');\n";
	print "window.location.replace('https://jv.umsa.bo/lostpassword.php');\n</script>";
	exit(0);
    }*/

<<<<<<< HEAD
if ($err_cnt>0){
	print "<script language='javascript'>\n";
	print "alert('";
    print $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);

}
$password=pwGen($_POST['password']);
$sql="SELECT user_id,email FROM users WHERE user_id = '$user_id' or email = '$email'";
=======
$sql      = "SELECT user_id,email FROM users WHERE email = '$email'";
$result   = mysql_query($sql);
>>>>>>> master

$rows_cnt = mysql_num_rows($result);
mysql_free_result($result);
if ($rows_cnt == 1){
	print "<script language='javascript'>\n";
	print "alert('Ya se encuentra registrado el correo, intenta con otro o recupera tu cuenta\\n');\n";
	print "window.location.replace('https://jv.umsa.bo/lostpassword.php');\n</script>";
	exit(0);
}

<<<<<<< HEAD
$sql="INSERT INTO `users`("
	."user_id,email,ip,accesstime,password,reg_time,nick,school,lastname,pais_id)"
    ."VALUES('".$user_id
=======
$nombre  = mysql_real_escape_string(htmlspecialchars ($nombre));
$email   = mysql_real_escape_string(htmlspecialchars ($email));
$ip      = $_SERVER['REMOTE_ADDR'];

$sql="INSERT INTO users(user_id,email,ip,accesstime,password,reg_time,nick,school,lastname,pais_id,obi,institucion_id)"
."VALUES('".$user_id
>>>>>>> master
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
<<<<<<< HEAD
//echo $sql."<br />";
=======
>>>>>>> master
$result=mysql_query($sql);
echo mysql_error();
while ($row=mysql_fetch_assoc($result)){
	$_SESSION[$row['rightstr']]=true;
    //echo $_SESSION[$row['rightstr']]."<br />";
}
  $_SESSION['ac']=Array();
  $_SESSION['sub']=Array();
?>
<script>history.go(-1);</script>
