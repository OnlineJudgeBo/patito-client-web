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
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg" />
		<style type='text/css'>strong,em{ font-weight: bold;} html{height: 100%;} body {min-height: 100%;}
		</style>

		<script src=" /util/materialize/materialize.min.js"></script>
		<link rel="stylesheet" href="./util/materialize/materialize.min.css"/>
		<link rel="stylesheet", href="https://fonts.googleapis.com/icon?family=Material+Icons"/>

		<script src='./util/react/react.development.js'></script>
		<script src='./util/react/react-dom.development.js'></script>
		<script src='./util/react/babel.min.js'></script>

		<script type="text/babel" src="./js/app.js"></script>
		
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

