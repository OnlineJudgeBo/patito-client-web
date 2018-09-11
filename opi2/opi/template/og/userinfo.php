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
		 crearTablaUserlog();
		 ?>
		 dat.getUser="<?php echo getUser();?>";
		 dat.userNick="<?php echo $nick;?>";
		 dat.userSchool="<?php echo $school;?>";
		 dat.userEmail="<?php echo $email;?>";
		 msg.mail="<?php echo $MSG_MAIL;?>";
		 function loadPag(){
			 if(!localStorage.getItem("skin")) localStorage.setItem("skin", 0);
			 ReactDOM.render(<Userinfo dat={dat} msg={msg} tabla={TablaUL} />, document.getElementById("userinfo"));
		 }
		 loadPag();
		</script>
		<title>Usuario</title>
	    <link rel="icon" type="image/png" href="template/og/image/juez-patito2.svg"/>
	</head>
	<body>
		<div id="userinfo">
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
						<tr bgcolor="#D7EBFF">
							<td width="15%"><?php echo $MSG_Number?>
							<td width=25% align=center><?php echo $Rank?>
							<td width=70% align=center>Problemas Resueltos
						</tr>
						<tr bgcolor="#D7EBFF">
							<td><?php echo $MSG_SOVLED?></td>
							<td align=center>
								<a href='status.php?user_id=<?php echo $user?>&jresult=4'><?php echo $AC?>
								</a></td>
							<td rowspan=14 align=center>
								<script language='javascript'>
								 function p(id){document.write("<a href=problem.php?id="+id+">"+id+" </a>");}
								 <?php $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id` ASC";	
								 if (!($result=mysql_query($sql))) echo mysql_error();
								 while ($row=mysql_fetch_array($result))
									 echo "p($row[0]);";
								 mysql_free_result($result);
								 ?>
								</script>
								<div id=submission style="width:600px;height:300px" ></div>
								
							</td>
						</tr>
						<tr bgcolor=#D7EBFF>
							<td><?php echo "Enviados"?></td>
							<td align=center>
								<a href='status.php?user_id=<?php echo $user?>'>
									<?php echo $Submit?></a>
							</td>
						</tr>
						<?php 
						foreach($view_userstat as $row){
							//i++;
							echo "<tr bgcolor=#D7EBFF><td>".$jresult[$row[0]]."<td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a></tr>";
						}


						//}
						echo "<tr id=pie bgcolor=#D7EBFF><td>Statistics<td><div id='PieDiv' style='position:relative;height:105px;width:120px;'></div></tr>";

						?>
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







