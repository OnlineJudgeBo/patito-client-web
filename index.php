<?php
////////////////////////////Common head
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Bienvenido al Juez de la Carrera de Informatica - UMSA";

///////////////////////////MAIN	
$view_news="";
$sql=	"SELECT * "
."FROM `news` "
."WHERE `defunct`!='Y'"
."ORDER BY `importance` ASC,`time` DESC "
."LIMIT 5";
	$result=mysql_query($sql);//mysql_escape_string($sql));

if (!$result){
	$view_news= "<h3>No hay noticias</h3>";
	$view_news.= mysql_error();
}else{
	$view_news.= "";
	while ($row=mysql_fetch_object($result)){
		$view_news.= "<b>".$row->title."</b>";
		$view_news.= "<h2>[".$row->user_id."]</h2>";
		$view_news.= $row->content;
	}
	mysql_free_result($result);
}
//////////////
/* Ultimos blog */
$view_blog="";
$sql=	"SELECT * "
."FROM `blog` "
."ORDER BY `date` DESC,`date` ASC "
."LIMIT 8";
	$result=mysql_query($sql);//mysql_escape_string($sql));
if (!$result){
	$view_blog= "<h3>El blog esta vacio :( </h3>";
		$view_blog.= mysql_error();
	}else{
		$view_news.= "";
		while ($row=mysql_fetch_object($result)){
			$view_blog.="<div id='blog_node'>";
			$view_blog.= "<div id='title'><a href=blog.php?blog=$row->blog_id>".$row->title."</a></div>";
			$view_blog.= "<div id='by'>Por [".$row->user_id."]</div>";
			if($_SESSION['user_id'] == $row->user_id){
				$view_blog.= "<b> <a href=blog_edit.php?blog=$row->blog_id > Editar </a></b>";
			}
			$view_blog.="<div id='content'>". $row->content."</div>";
			//cargar los comentarios segun el blog construccion
			//$view_blog.= "<p>"$row->comets;
			$view_blog.='</div>';
		}
		//mysql_free_result($result);
	}


//
	$view_apc_info="";

	$sql="SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc ";
	$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_all= array();
//echo $sql;

while ($row=mysql_fetch_array($result)){
	$chart_data_all[$row['md']]=$row['c'];
}

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc ";
	$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_ac= array();
//echo $sql;

while ($row=mysql_fetch_array($result)){
	$chart_data_ac[$row['md']]=$row['c'];
}




if(function_exists('apc_cache_info')){
	$_apc_cache_info = apc_cache_info(); 
	$view_apc_info =_apc_cache_info;
}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/index.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
