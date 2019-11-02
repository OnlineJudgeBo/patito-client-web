<?php
require_once('./include/db_info.inc.php');
<<<<<<< HEAD
//antes del tamplate
if(function_exists('apc_cache_info')){
	$_apc_cache_info = apc_cache_info(); 
	$view_apc_info =_apc_cache_info;
=======
require_once('./include/setlang.php');
$view_title= "Bienvenido al Juez de la Carrera de Informatica - UMSA";

///////////////////////////MAIN	
$view_news="";
$sql=	"SELECT * "
."FROM `news` "
."WHERE `defunct`!='Y'"
."ORDER BY `importance` ASC,`time` DESC "
."LIMIT 1";
	$result=mysql_query($sql);//mysql_escape_string($sql));

if (!$result){
	$view_news= "";
	$view_news.= mysql_error();
}else{
	$view_news.= "";
	while ($row=mysql_fetch_object($result)){
		$view_news.= "<h3>".$row->title."</h3><br>";
		$view_news.= $row->content;
	}
	mysql_free_result($result);
}
//////////////
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
>>>>>>> master
}
if($OJ_ONLINE){
	require_once('./include/online.php');
	$on = new online();
}?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		
		<script src=" /util/materialize/materialize.min.js"></script>
		<link rel="stylesheet" href="./util/materialize/materialize.min.css"/>
		<link rel="stylesheet", href="https://fonts.googleapis.com/icon?family=Material+Icons"/>

		<script src='./util/react/react.development.js'></script>
		<script src='./util/react/react-dom.development.js'></script>
		<script src='./util/react/babel.min.js'></script>

		<script type="text/babel" src="./js/app.js"></script>

		<script src='./util/showdown/showdown.min.js'></script>
		<script>showdown.setOption('tables', 1);
		 showdown.setOption('headerLevelStart', 3);
		 showdown.setOption('emoji',1);
		 showdown.setOption('literalMidWordUnderscores',0);
		 showdown.setOption('literalMidWordAsterisks',0);</script>
		
		<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-MML-AM_CHTML' async></script>
		<script type='text/x-mathjax-config'>
		 MathJax.Hub.Config({
			 tex2jax: {inlineMath: [['$','$']]}
         });</script>

		<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js'></script>

		<style type='text/css'>strong,em{font-weight: bold;} html{min-height: 100%; position: relative;} body{margin:0;}</style>
		
		<script type="text/babel">
		 <?php require_once("./init.php");
         if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
             require_once("include/set_get_key.php");
             echo "dat.getKey=\"".$_SESSION['getkey']."\";";
         }?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Judgeduck dat={dat} msg={msg} page={"home"} />,
							 document.getElementById("content"));
		 }
		 loadPag();
		</script>		
		<title>Bienvenido al Juez de la Carrera de Informatica - UMSA BETA</title>
	</head>
	<body>
		<div id="content">
			<center><div class="preloader-wrapper active">
				<div class="spinner-layer spinner-red-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div><div class="gap-patch">
						<div class="circle"></div>
					</div><div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div></center>
		</div>		
	</body>
</html>
