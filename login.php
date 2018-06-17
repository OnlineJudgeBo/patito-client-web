<?php 
    require_once("./include/db_info.inc.php");
	require_once("./include/login-".$OJ_LOGIN_MOD.".php");
    $user_id=$_POST['user_id'];
	$password=$_POST['password'];
   if (get_magic_quotes_gpc ()) {
        $user_id= stripslashes ( $user_id);
        $password= stripslashes ( $password);
   }
    $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".mysql_real_escape_string($user_id)."'";
    $result=mysql_query($sql);
	$login=check_login($user_id,$password);
	
	if ($login){
		$_SESSION['user_id']=$login;
		
		echo mysql_error();
		while ($result&&$row=mysql_fetch_assoc($result))
			$_SESSION[$row['rightstr']]=true;
		
		$sql = "SELECT user_id FROM users where user_id ='".$user_id."' and school !='-' ";
		$result=mysql_query($sql);
		if(mysql_numrows($result) > 0){
			$sql2 = "UPDATE users SET school='-' WHERE user_id= '".$user_id."'";
			$tt = mysql_query($sql2);
			echo "<script language='javascript'>\n";
			echo "alert('Actualice sus datos gracias.');\n";
			echo "window.location='modifypage.php'";
			echo "</script>";
			
		}else{
			echo "<script language='javascript'>\n";
            echo "window.history.back()\n";
			echo "history.go(-2);\n";
			echo "</script>";
		}
	}else{
		echo "<script language='javascript'>\n";
		echo "alert('UserName or Password Wrong!');\n";
		echo "history.go(-1);\n";
		echo "</script>";
	}
?>


