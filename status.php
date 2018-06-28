<?php header("Cache-Control: no-cache, must-revalidate");// HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");// Date in the past

////////////////////////////Common head
$cache_time     = 2;
$OJ_CACHE_SHARE = false;
require_once ('./include/cache_start.php');
require_once ('./include/db_info.inc.php');?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app");</script>
		<script type="text/babel">
		 <?php require_once("./init.php");
         crearTablaStatus();
		 ?>
		 dat.getProblemId="<?php echo getProblemId();?>";
		 dat.getUserId="<?php echo getUserId();?>";
		 dat.getCid="<?php echo getCid();?>";
		 dat.languageName=["<?php echo implode("\",\"",$language_name);?>"];
		 dat.getLanguage="<?php echo getLanguage();?>";
		 dat.jresult=["<?php echo implode("\",\"",$jresult);?>"];
		 dat.simArr=["<?php echo implode("\",\"",$sim_arr);?>"];
		 dat.PID=["<?php echo implode("\",\"",$PID);?>"];
		 dat.getJresult="<?php echo getJresult();?>";
		 dat.getShowsim="<?php echo getShowsim();?>";
		 dat.getGet="<?php echo getGet();?>";
		 dat.getPrevtop="<?php if(isset($_GET['prevtop'])) echo $_GET['prevtop'];?>";
		 dat.top="<?php if(isset($top)) echo $top?>";
		 dat.bottom="<?php if(isset($bottom)) echo $bottom?>";
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Status dat={dat} msg={msg} tabla={TablaS}/>,
							 document.getElementById("content"));
		 }
		 loadPag();
		</script>
		<title>Estado</title>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg" />
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
			</center></div>
		</div>
	</body>	
</html>
<?php if (file_exists('./include/cache_end.php')) {
	require_once ('./include/cache_end.php');
}?>

