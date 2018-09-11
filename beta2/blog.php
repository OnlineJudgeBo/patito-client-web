<?php	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	
	
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>Please LogIn First!</a>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	}
	$view_title= "Blogs";
	$blog_id=$_GET['blog'];
	if($blog_id){
	$sql=	"SELECT * "
			."FROM `blog` "
			."WHERE blog_id = $blog_id";
	}else{
	$sql=	"SELECT * "
			."FROM `blog` "
			."LIMIT 12";
	}

	$view_blog="";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if($rows_cnt == 0){
		$view_blog= "<h3>Este blog no existe :( </h3>";
		$view_blog.= mysql_error();
	}else if (!$result){
		$view_blog= "<h3>No hay blogs creados :( </h3>";
		$view_blog.= mysql_error();
	}else{
		while ($row=mysql_fetch_object($result)){
			$view_blog.= "<h2>".$row->title."</h2>";
			$view_blog.= "Por este  <b>[".$row->user_id."]</b>";
			//si es dueño del blog puede editarlo
			if($_SESSION['user_id'] == $row->user_id){
				$view_blog.= "<b> <a href=blog_edit.php?blog=$row->blog_id > Editar </a></b>";
			}
			$view_blog.= "<p>".$row->content."</p>";
		}

		$sql="SELECT * "
			."FROM `blog_coments` "
			."WHERE blog_id = $blog_id";
		$result=mysql_query($sql);
	    $rows_cnt=mysql_num_rows($result);
	   while ($row=mysql_fetch_object($result)){
			$view_comment.= "<div id='comment_node'>";
			$view_comment.= "<div id='by'>".$row->user_id."</div>";
			//si es dueño del comentario puede editarlo
			if($_SESSION['user_id'] == $row->user_id){
				//$view_comment.= "<b> <a href=blog_edit.php?blog=$row->blog_id > Editar </a></b>";
			}
			$view_comment.= "<div id='comment'>".$row->comment."</div>";
			$view_comment.="</div>";

		}
		
	
	}
	
	
$comment=$_POST['jalar'];
	//$comment=$_POST['comment'];
	$user_id=$_SESSION['user_id'];
	


	if($comment!=""){
		$sql=" INSERT INTO `blog_coments`(`blog_id`, `comment`, `user_id`) 
		VALUES ('$blog_id', '$comment', '$user_id' )";
		mysql_query($sql);
		header("location:./blog.php?blog=$blog_id");
	}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/blogview.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
