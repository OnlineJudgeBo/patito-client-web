<?php $OJ_CACHE_SHARE=!isset($_GET['cid']);
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php'); ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>		
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app", "showdown", "mathjax", "chartjs");</script>
		<script type="text/babel">
		 <?php require_once("./init.php"); contest();
		 if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
			 // para editar los problemas de los contests
			 require_once("include/set_get_key.php");
			 echo "dat.getKey=\"".$_SESSION['getkey']."\";";
		 }?>
		 function loadPag(){ // load para cargar el skin de nuevo
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 if(dat.error){
				 ReactDOM.render(<Errorpage dat={dat} msg={msg} />,
			 					 document.getElementById("content"));
			 }else{
				 ReactDOM.render(<Contestpage dat={dat} msg={msg}/>,
								 document.getElementById("content"));
			 }
			 MathJax.Hub.Typeset();
		 }
		 loadPag();
		</script>
		<title><?php echo $view_title?></title>
        <div id="fb-root"></div>
		<script>(function(d, s, id) {
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) return;
			 js = d.createElement(s); js.id = id;
			 js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=1505282666151605&version=v2.0";
			 fjs.parentNode.insertBefore(js, fjs);
		 }(document, 'script', 'facebook-jssdk'));</script>
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
<?php if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php'); ?>

