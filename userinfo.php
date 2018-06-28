<?php
$cache_time=10; 
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("./initPHP.php");
// check user
$user=$_GET['user'];
if (!is_valid_user_name($user)){
    $view_errors="</br></br></br><h3>Usuario no valido!...</h3></br></br></br>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
$view_title=$user;
$user_mysql=mysql_real_escape_string($user);
$sql="SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`='$user_mysql'";
$result=mysql_query($sql);
$row_cnt=mysql_num_rows($result);
if ($row_cnt==0){
    $view_errors="</br></br></br><h3>No hay tal usuario!...</h3></br></br></br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
$row=mysql_fetch_object($result);
$school=$row->school;
$email=$row->email;
$nick=$row->nick;
mysql_free_result($result);
// count solved
$sql="SELECT count(DISTINCT problem_id) as `ac` FROM `solution` WHERE `user_id`='".$user_mysql."' AND `result`=4";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$AC=$row->ac;
mysql_free_result($result);
// count submission
$sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$Submit=$row->Submit;
mysql_free_result($result);
// update solved 
$sql="UPDATE `users` SET `solved`='".strval($AC)."',`submit`='".strval($Submit)."' WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql);
$sql="SELECT count(*) as `Rank` FROM `users` WHERE `solved`>$AC";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$Rank=intval($row[0])+1;

$i=0;
$sql="SELECT result,count(1) FROM solution WHERE `user_id`='$user_mysql'  AND result>=4 group by result order by result";
$result=mysql_query($sql);
$view_userstat=array();
while($row=mysql_fetch_array($result)){
    $view_userstat[$i++]=$row;
}
mysql_free_result($result);

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql'   group by md order by md desc ";
$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_all= array();
//echo $sql;
    
while ($row=mysql_fetch_array($result)){
    $chart_data_all[$row['md']]=$row['c'];
}
    
$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and result=4 group by md order by md desc ";
$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_ac= array();
//echo $sql;
    
while ($row=mysql_fetch_array($result)){
    $chart_data_ac[$row['md']]=$row['c'];
}
  
mysql_free_result($result);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="./js/load.js"></script>
		<script>load("materialize", "react", "app");</script>
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
			 ReactDOM.render(<Userinfo dat={dat} msg={msg} tabla={TablaUL} />,
							 document.getElementById("userinfo"));
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
<?php if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

