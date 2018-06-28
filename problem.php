<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
if (isset($_GET['cid']) && !isset($_GET['pid'])){
	require("contest.php");
	exit(0);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app", "showdown", "mathjs");</script>
		<script type="text/babel">
		 <?php require_once("./init.php");
		 problem();
		 if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
			 require_once("include/set_get_key.php");
			 echo "dat.getKey=\"".$_SESSION['getkey']."\";";
		 }?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 if(dat.error){
			 	 ReactDOM.render(<Errorpage dat={dat} msg={msg} />,
			 					 document.getElementById("content"));
			 }else{
			 	 ReactDOM.render(<Problempage dat={dat} msg={msg} />,
								 document.getElementById("content"));
			 }
		 }
		 loadPag();
		</script>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		<title><?php echo $view_title?></title>
	</head>
	<body>
		<div id="content">
			<center><div class="preloader-wrapper active center">
				<div class="spinner-layer spinner-red-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="gap-patch"><div class="circle"></div></div>
					<div class="circle-clipper right"><div class="circle"></div></div>
				</div>
			</center></div>
		</div>
	</body>
</html>
<?php if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
