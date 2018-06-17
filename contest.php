<div id="fb-root"></div>
<script>(function(d, s, id) {
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) return;
	 js = d.createElement(s); js.id = id;
	 js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=1505282666151605&version=v2.0";
	 fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));</script>
<?php
$OJ_CACHE_SHARE=!isset($_GET['cid']);
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("initPHP.php");
if (isset($_GET['cid'])){
 	$cid=intval($_GET['cid']);
 	$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
 	$result=mysql_query($sql); 	
 	if (mysql_num_rows($result)==0){
 		mysql_free_result($result);
		$view_errors="<h3>No hay tal contest!...</h3>";
 		require("template/".$OJ_TEMPLATE."/error.php");
 		exit(0);
 	}
	if(!isset($_SESSION['administrator'])){
		$contest_ok=true;
 		$row=mysql_fetch_object($result);
 		if ($row->private && !isset($_SESSION['c'.$cid]))$contest_ok=false;
 		if ($row->defunct=='Y') $contest_ok=false;
 		if (time()<strtotime($row->start_time)){
 			$view_errors="<h3>Recien iniciara el concurso!...</h3>".$row->start_time;
 			require("template/".$OJ_TEMPLATE."/error.php");
 			exit(0);
 		}
 		if (!$contest_ok){
 			$view_errors="<h3>$MSG_PRIVATE_WARNING <a href=contestrank.php?cid=$cid>".
						 $MSG_WATCH_RANK."</a></h3>";
 			require("template/".$OJ_TEMPLATE."/error.php");
 			exit(0);
 		}
	}    
	$row=mysql_fetch_object($result);
}

/////////////////////////Template
if(isset($_GET['cid'])){
	require("template/".$OJ_TEMPLATE."/contest.php");
}else
require("template/".$OJ_TEMPLATE."/contestset.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

