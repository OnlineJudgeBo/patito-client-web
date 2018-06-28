<?php $OJ_CACHE_SHARE=false;
$cache_time=30;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app");</script>
		<script type="text/babel">
		 <?php require_once("./init.php");
		 crearTablaRanklist();
		 ?>
		 dat.pageTotal="<?php echo $view_total;?>";
		 dat.pageSize="<?php echo $page_size;?>";
		 dat.getScope="<?php echo getScope();?>";
		 dat.getUserId="<?php echo getUserId();?>";
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Ranklist dat={dat} msg={msg} tabla={TablaR} />,
							 document.getElementById("content"));
		 }
		 loadPag();
		</script>
		<title><?php echo $view_title?></title>
	    <link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
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
    require_once('./include/cache_end.php');
?>

