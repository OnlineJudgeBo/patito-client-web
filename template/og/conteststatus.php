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
		<script type="text/babel">
		 <?php require_once("./init.php");
		 crearListStatusTabla();
		 ?>
		 dat.getProblemId="<?php echo getProblemId();?>";
		 dat.getUserId="<?php echo getUserId();?>";
		 dat.getCid="<?php echo getCid();?>";
		 dat.languageName=["<?php echo implode("\",\"",$language_name);?>"];
		 dat.getLanguage="<?php echo getLanguage();?>";
		 dat.jresult=["<?php echo implode("\",\"",$jresult);?>"];
		 dat.getJresult="<?php echo getJresult($jresult);?>";
		 dat.getShowsim="<?php echo getShowsim();?>";
		 dat.getGet="<?php echo getGet();?>";
		 dat.getPrevtop="<?php if(isset($_GET['prevtop'])) echo $_GET['prevtop'];?>";
		 dat.top="<?php if(isset($top)) echo $top?>";
		 dat.bottom="<?php if(isset($botrom)) echo $bottom?>";
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Status dat={dat} msg={msg} tabla={TablaS} />,
							 document.getElementById("status"));
		 }
		 loadPag();
		</script>
		<!-- <meta http-equiv='refresh' content='60'>-->
		<title>Estado</title>
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg" />
	</head>
	<body>
		<div id="status">
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
