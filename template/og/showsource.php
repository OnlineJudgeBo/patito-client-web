<?php $view_title= "Código Fuente"; ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--Let browser know website is optimized for mobile-->
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
		<link rel="stylesheet" href="./materialize/materialize.min.css">
		<script src="./materialize/materialize.min.js"></script>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="./react/react.development.js"></script>
		<script src="./react/react-dom.development.js"></script>
		<script src="./react/babel.min.js"></script>
		<script type="text/babel" src="./react/app.js"></script>

		
		<title><?php echo $view_title?></title>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg" />

		<script type="text/babel">
		 <?php require_once("./init.php");
		 crearCodigoFuente();?>
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<ShowSource dat={dat} msg={msg} />, document.getElementById("showSource"));
		 }
		 loadPag();
		 
		</script>

		<link href="prism/prism.css" rel="stylesheet" />
		<script src="prism/prism.js"></script>
		
	</head>
	<body>
		
		<div id="showSource">
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



