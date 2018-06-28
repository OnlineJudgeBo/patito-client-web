<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--Let browser know website is optimized for mobile-->
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
		<script src="./js/load.js"></script> 
		<script>load("materialize", "react", "app");</script>
		<script type="text/babel">			
		 <?php require_once("./init.php");?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Login dat={dat} msg={msg} />, document.getElementById("login"));
		 }
		 loadPag();
		</script>		
		<title><?php echo $view_title?></title>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
    </head>
    <body>
		<div id="login">
			<div class="preloader-wrapper active">
				<div class="spinner-layer spinner-red-only">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div><div class="gap-patch">
						<div class="circle"></div>
					</div><div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>
