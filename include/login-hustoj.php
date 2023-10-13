<?php 
	require_once("./include/my_func.inc.php");
    
	function check_login($user_id,$password){
		$user_id=mysql_escape_string($user_id);
		$pass2 = 'No Saved';
		session_destroy();
		session_start();

		$sql = "INSERT INTO `loginlog` VALUES(NULL, '$user_id','$pass2','".$_SERVER['REMOTE_ADDR']."',NOW())";
		@mysql_query($sql) or die(mysql_error());
		$sql="SELECT `user_id`,`password`, accesstime FROM `users` WHERE `user_id`='".$user_id."'";
		$result=mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row && pwCheck($password,$row['password'])){
			$user_id=$row['user_id'];
                        $accesstime = $row['accesstime'];

                        update_fisrt_login($user_id,$accesstime);
			mysql_free_result($result);
			return $user_id;
		}
		mysql_free_result($result);
		return false; 
	}

       function update_fisrt_login($user_id, $accesstime){
                if($accesstime != "0000-00-00 00:00:00") {
                   return;
                }
                $sql = sprintf("UPDATE users SET accesstime=%s  WHERE user_id = '%s' ","NOW()",$user_id);
                @mysql_query($sql);
       }
?>
