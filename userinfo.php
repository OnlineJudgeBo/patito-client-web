<?php
$cache_time=10; 
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php'); ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
		
		<script src=" /util/materialize/materialize.min.js"></script>
		<link rel="stylesheet" href="./util/materialize/materialize.min.css"/>
		<link rel="stylesheet", href="https://fonts.googleapis.com/icon?family=Material+Icons"/>

		<script src='./util/react/react.development.js'></script>
		<script src='./util/react/react-dom.development.js'></script>
		<script src='./util/react/babel.min.js'></script>

		<script type="text/babel" src="./js/app.js"></script>
		<style type='text/css'>strong,em{ font-weight: bold;}</style>
		
		<script type="text/babel">
		 <?php require_once("./init.php");
		 userInfo(); ?>		 
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 if(dat.error){
				 ReactDOM.render(<Errorpage dat={dat} msg={msg}/>,
								 document.getElementById("content"));
			 }else{
				 ReactDOM.render(<Userinfo dat={dat} msg={msg}/>,
								 document.getElementById("userinfo"));
			 }
		 }
		 loadPag();
		</script>
		<title>Usuario</title>
	    <link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
	</head>
	<body>
		<div id="userinfo">
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
			</div></center>
		</div>		
		<script type="text/javascript" src="include/wz_jsgraphics.js"></script>
		<script type="text/javascript" src="include/pie.js"></script>
		<script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script>
		<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
		<script type="text/javascript">
		 $(function () {
			 var d1 = [];
			 var d2 = [];
			 <?php 
			 foreach($chart_data_all as $k=>$d){
			 ?>
			 d1.push([<?php echo $k?>, <?php echo $d?>]);
			 <?php }?>
			 <?php 
			 foreach($chart_data_ac as $k=>$d){
			 ?>
			 d2.push([<?php echo $k?>, <?php echo $d?>]);
			 <?php }?>
			 //var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

			 // a null signifies separate line segments
			 var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
			 
			 $.plot($("#submission"), [ 
    			 {label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
    			 {label:"<?php echo $MSG_AC?>",data:d2,bars:{show:true}} ],{
    				 
    				 
    				 xaxis: {
    					 mode: "time"
						 //,    max:(new Date()).getTime()
						 //,min:(new Date()).getTime()-100*24*3600*1000
					 },
					 grid: {
						 backgroundColor: { colors: ["#fff", "#333"] }
					 }
				 });
		 });
		 //alert((new Date()).getTime());
		</script>
		<div id="wrapper">
			<section id="main">
				<center>
					<table class="table table-striped" id="statics" width="70%">
						
						<script language="javascript">
						 var y= new Array ();
						 var x = new Array ();
						 var dt=document.getElementById("statics");
						 var data=dt.rows;
						 var n;
						 for(var i=3;dt.rows[i].id!="pie";i++){
							 n=dt.rows[i].cells[0];
							 n=n.innerText || n.textContent;
							 x.push(n);
							 n=dt.rows[i].cells[1].firstChild;
							 n=n.innerText || n.textContent;
							 //alert(n);
							 n=parseInt(n);
							 y.push(n);
						 }
						 var mypie=  new Pie("PieDiv");
						 mypie.drawPie(y,x);
						 //mypie.clearPie();
						</script>
					</table>
				</center>
			</section>
		</div>
	</body>
</html>
<?php if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

