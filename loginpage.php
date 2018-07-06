<?php
$cache_time=1;
require_once('./include/cache_start.php');
require_once("./include/db_info.inc.php");
$view_title= "LOGIN";

if (isset($_SESSION['user_id'])){
	echo "<a href=logout.php>Please logout First!</a>";
	exit(1);
}?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		<style type='text/css'>strong,em{ font-weight: bold;} html{min-height: 100%;}</style>
		
		<script src=" /util/materialize/materialize.min.js"></script>
		<link rel="stylesheet" href="./util/materialize/materialize.min.css"/>
		<link rel="stylesheet", href="https://fonts.googleapis.com/icon?family=Material+Icons"/>

		<script src='./util/react/react.development.js'></script>
		<script src='./util/react/react-dom.development.js'></script>
		<script src='./util/react/babel.min.js'></script>

		<script type="text/babel" src="./js/app.js"></script>
		
		<script type="text/babel">			
		 <?php require_once("./init.php");
         registerPage();?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Login dat={dat} msg={msg} />, document.getElementById("content"));
		 }
		 loadPag();
		</script>		
		<title><?php echo $view_title?></title>		
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
    require_once('./include/cache_end.php'); ?>

