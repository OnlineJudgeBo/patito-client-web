<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app", "showdown", "mathjs");</script>
		<script type="text/babel">
		 <?php require_once("./init.php");
		 crearContestRank();
		 crearDatosContestProblemSet();
		 ?>		 
		 dat.title="<?php echo $title;?>";
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Contestrank dat={dat} msg={msg} tabla={TablaCR}/>,
							 document.getElementById("content"));
		 }
		 loadPag();
		</script>
		<title><?php echo $title; ?></title>
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
