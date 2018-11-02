<?php  
    require("../include/db_info.inc.php");
	require_once("../lang/$OJ_LANG.php");
	
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel=stylesheet href='../template/<?php echo $OJ_TEMPLATE?>/hoj.css' type='text/css'>
</head>
<?php if(isset($_GET['cid']))
	$cid=intval($_GET['cid']);
if (isset($_GET['pid']))
	$pid=intval($_GET['pid']);
?>

<div id="header">
	<div id="logo">
		<div id="logo_text">
			<h1 id="logo_colour">Bienvenido al Juez de la Carrera de Informatica - UMSA</h1>
			<h2>Un humilde judge de practicas</h2>
		</div>
	</div>
	<ul id="sdt_menu" class="sdt_menu">	
		<li id="contest_event">
			<a href="<?php echo $OJ_HOME?>" >
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/home.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_HOME?></span>
					<span class="sdt_descr">Main</span>
				</span>						
			</a>
		</li>
		<li>
			<a href='../bbs.php?cid=<?php echo $cid?>' >
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/help.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_BBS?></span>
					<span class="sdt_descr">Preguntas ? </span>
				</span>						
			</a>
		</li>
		<li>
			<a href='../contest.php?cid=<?php echo $cid?>'>
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/practica.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_PROBLEMS?></span>
					<span class="sdt_descr">Problemas</span>
				</span>			
			</a>
		</li>
		<li>
			<a href='../contestrank.php?cid=<?php echo $cid?>'>
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/concursos.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_STANDING?></span>
					<span class="sdt_descr">Posiciones</span>
				</span>			
			</a>
		</li>
		<li>
			<a href='../status.php?cid=<?php echo $cid?>'>
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/concursos.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_SEEOJ?></span>
					<span class="sdt_descr">Ultimos envios</span>
				</span>			
			</a>
		</li>
		<li>
			<a href='../conteststatistics.php?cid=<?php echo $cid?>'>
				<img src="../template/<?php echo $OJ_TEMPLATE?>/image/ranklist.png" alt=""/>
				<span class="sdt_active"></span>
				<span class="sdt_wrap">
					<span id="sdt_link" class="sdt_link"><?php echo $MSG_STATISTICS?></span>
					<span class="sdt_descr">Estadistica</span>
				</span>			

			</a>
		</li>
	</ul>

</div>
<div id="profile" >
	<script src="../include/profile.php?<?php echo rand();?>" ></script>
</div><!--end profile-->

<div id="broadcast" class="container">
	<marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';>
		<?php echo file_get_contents("../admin/msg.txt");?>
	</marquee>
</div>
<?php
$contest_ok=true;
$str_private="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' && `private`='1'";
$result=mysql_query($str_private);
$row=mysql_fetch_row($result);
mysql_free_result($result);
if ($row[0]=='1' && !isset($_SESSION['c'.$cid])) $contest_ok=false;
if (isset($_SESSION['administrator'])) $contest_ok=true;
?>



