<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Agregar Blog";
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>Please LogIn First!</a>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}
	$user_id=$_SESSION['user_id'];
	$title=trim($_POST['title']);
	$content=trim($_POST['content']);
	$date=date("Y-m-d H:i:s");
	$blog_id=$_POST['edit'];
	if($blog_id!=""){
		$sql=  "SELECT * "
			."FROM `blog` "
			."WHERE blog_id=$blog_id";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	if($row && $row->user_id == $user_id){
		$title=trim($_POST['title']);
		$content=trim($_POST['content']);
		$sql="UPDATE `blog`"
			."SET `title`='$title', `content`='$content' where `blog_id`='$blog_id'";
   		mysql_query($sql);
		header("Refresh:0; url=blog.php?blog=$blog_id");
	}else{
		$row="";
		echo "<div id='sms'>No es propietario de este blog</div>";
	}
}else if($user_id!=""  && $content!="" && $title!=""){
	$sql="INSERT INTO `blog`(`user_id`,`title`,`date`,`content`)
	VALUES('$user_id','$title','$date' ,'$content')";
	mysql_query($sql);

	header("location:./");
}else{
}
					

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/blogpage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

