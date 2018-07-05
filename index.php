<?php
$OJ_CACHE_SHARE=false;
$cache_time=10;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
//antes del tamplate
if(function_exists('apc_cache_info')){
	$_apc_cache_info = apc_cache_info(); 
	$view_apc_info =_apc_cache_info;
}
if($OJ_ONLINE){
	require_once('./include/online.php');
	$on = new online();
}?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		<style type='text/css'>strong,em{font-weight: bold;}</style>

		<script src=" /util/materialize/materialize.min.js"></script>
		<link rel="stylesheet" href="./util/materialize/materialize.min.css"/>
		<link rel="stylesheet", href="https://fonts.googleapis.com/icon?family=Material+Icons"/>

		<script src='./util/react/react.development.js'></script>
		<script src='./util/react/react-dom.development.js'></script>
		<script src='./util/react/babel.min.js'></script>

		<script type="text/babel" src="./js/app.js"></script>
		
		<script type="text/babel">
		 <?php require_once("./init.php");
		 crearlistContest();?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Index dat={dat} msg={msg} list={listContest}/>,
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
<?php if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
