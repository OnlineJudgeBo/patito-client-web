<?php  
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("./lang/$OJ_LANG.php");
?>
<?php if(isset($_GET['cid']))
$cid=intval($_GET['cid']);
if (isset($_GET['pid']))
	$pid=intval($_GET['pid']);
?>

<div id="header">
	<div id="logo"></div>
	
   <div id="menu">
	<ul >	
		<li>
			<a href="<?php echo $OJ_HOME?>" >
            Principal
         </a>
		</li>
		<!--<li>
			<a href='./bbs.php?cid=<?php echo $cid?>' >
				<?php echo $MSG_BBS?>	
			</a>
		</li>
      -->
		<li>
			<a href='./contest.php?cid=<?php echo $cid?>'>
				<?php echo $MSG_PROBLEMS?>
			</a>
		</li>
		<li>
			<a href='./contestrank.php?cid=<?php echo $cid?>'>
				<?php echo $MSG_STANDING?>
			</a>
		</li>
		<li>
			<a href='./status.php?cid=<?php echo $cid?>'>
				<?php echo $MSG_SEEOJ?>
			</a>
		</li>
		<li>
			<a href='./conteststatistics.php?cid=<?php echo $cid?>'>
				<?php echo $MSG_STATISTICS?>
			</a>
		</li>
	</ul>
	</div>

<div id="profile" >
	<script src="include/profile.php?<?php echo rand();?>" ></script>
</div><!--end profile-->
</div>
<div id="broadcast" class="container">
	<marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';>
		<?php echo file_get_contents("./admin/msg.txt");?>
	</marquee>
</div>
