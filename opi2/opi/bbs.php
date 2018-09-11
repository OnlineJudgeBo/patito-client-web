<?php
echo "asfaf";
require_once("./include/db_info.inc.php");
require_once("./initPHP.php");
if(isset($_GET['pid']))
	$pid=intval($_GET['pid']);
else
	$pid=0;
if(isset($_GET['cid']))
	$cid=intval($_GET['cid']);
else
	$cid=0;

if($OJ_BBS=="discuss"){
//echo ("<script>location.href='discuss/discuss.php?".$_SERVER["QUERY_STRING"]."';</script>");
require_once("discuss/discuss.php");
}else{
$url="";
if(isset($_GET['pid'])){
$url=("bbs/search.php?fid[]=2&keywords=".$pid); //chenge this to your own phpBB search link
}else{
$url=("bbs/");
}
echo ("<script>location.href='".$url."';</script>");
}
echo "termino";
?>
