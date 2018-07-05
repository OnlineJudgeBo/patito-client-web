<?php $cache_time=30; $OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php'); ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		<style type='text/css'>strong,em{ font-weight: bold;}</style>
		
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
		
		<script type="text/babel">
		 <?php require_once("./init.php"); problem();
		 if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
			 require_once("include/set_get_key.php");
			 echo "dat.getKey=\"".$_SESSION['getkey']."\";";
		 }?>
		 function loadPag(){ // load para cargar el skin de nuevo
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 if(dat.error){
				 ReactDOM.render(<Errorpage dat={dat} msg={msg} />,
								 document.getElementById("content"));
			 }else{
				 ReactDOM.render(<Problempage dat={dat} msg={msg} />,
								 document.getElementById("content"));
			 }
			 MathJax.Hub.Typeset();
		 }
		 loadPag();
		</script>
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
    require_once('./include/cache_end.php'); ?>
