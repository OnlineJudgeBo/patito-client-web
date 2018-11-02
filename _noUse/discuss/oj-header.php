<?php 
require('../include/db_info.inc.php');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel=stylesheet href='../template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	<?php function checkcontest($MSG_CONTEST){
		require_once("../include/db_info.inc.php");
		$sql="SELECT count(*) FROM `contest` WHERE `end_time`>NOW() AND `defunct`='N'";
		$result=mysql_query($sql);
		$row=mysql_fetch_row($result);
		if (intval($row[0])==0) $retmsg=$MSG_CONTEST;
		else $retmsg=$row[0]."<font color=red>&nbsp;$MSG_CONTEST</font>";
		mysql_free_result($result);
		return $retmsg;
	}
	function checkmail(){
		require_once("../include/db_info.inc.php");
		$sql="SELECT count(1) FROM `mail` WHERE 
		new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
		$result=mysql_query($sql);
		if(!$result) return false;
		$row=mysql_fetch_row($result);
		$retmsg="<font color=red>(".$row[0].")</font>";
		mysql_free_result($result);
		return $retmsg;
	}
	
	require_once("../lang/en.php");
	
	

	if($OJ_ONLINE){
		require_once('../include/online.php');
		$on = new online();
	}
	?>
	<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#sdt_menu > li').bind('mouseenter',function(){
				var $elem = $(this);
				$elem.find('img')
				.stop(true)
				.animate({
					'width':'121px',
					'height':'121px',
					'left':'0px'
				},400,'easeOutBack')
				.andSelf()
				.find('.sdt_wrap')
				.stop(true)
				.animate({'top':'96px'},500,'easeOutBack')
				.andSelf()
				.find('.sdt_active')
				.stop(true)
				.animate({'height':'121px'},300,function(){
					var $sub_menu = $elem.find('.sdt_box');
					if($sub_menu.length){
						var left = '121px';
						if($elem.parent().children().length == $elem.index()+1)
							left = '-121px';
						$sub_menu.show().animate({'left':left},200);
					}	
				});
			}).bind('mouseleave',function(){
				var $elem = $(this);
				var $sub_menu = $elem.find('.sdt_box');
				if($sub_menu.length)
					$sub_menu.hide().css('left','0px');

				$elem.find('.sdt_active')
				.stop(true)
				.animate({'height':'0px'},300)
				.andSelf().find('img')
				.stop(true)
				.animate({
					'width':'0px',
					'height':'0px',
					'left':'85px'},400)
				.andSelf()
				.find('.sdt_wrap')
				.stop(true)
				.animate({'top':'25px'},500);
			});
		});
</script>
</head>
<div id="header">
	
	<div id="menu">
			<div id="logo"></div> 	
	
		<ul>	
			<li><a href="<?php echo $OJ_HOME?>">
					<?php echo $MSG_HOME?>
				</a>
			</li>
			<li >
			<a href="../problemset.php">
				Problemas

			</a>
			</li>
			<li><a href="../bbs.php" >
						<?php echo $MSG_BBS?>
				</a>
			</li> 
			<li><a href="../submitpage.php">
						Evaluar
				</a>
			</li>
			<li>
				<a href="../status.php">
					Estado del juez
				</a>
			</li>
			<li><a href="../ranklist.php">
						<?php echo $MSG_RANKLIST?>
				</a>
			</li>

			<li><a  href="../contest.php">
						<?php echo checkcontest($MSG_CONTEST)?>	
				</a>
			</li>
			
			

	<!--<a class='btn <?php if ($url=="recent-contest.php") echo " $ACTIVE";?>' href="recent-contest.php">
		<i class="icon-share"></i><?php echo "$MSG_RECENT_CONTEST"?></a>
	-->
		<li ><a href="../faqs.php">
			<?php echo "$MSG_FAQ"?>	
			</a>
		</li>
</ul>
</div>


<div id="profile" >

	<?php if (isset($_SESSION['user_id'])){
		$sid=$_SESSION['user_id'];
		print "&nbsp;<a href=../modifypage.php>$MSG_USERINFO
	</a><a href='../userinfo.php?user=$sid'>
	<font color=red>$sid</font></a>";
	$mail=checkmail();
	if ($mail)
		print "<a href=../mail.php>$mail</a>";
	print "<a href=../logout.php>$MSG_LOGOUT</a>";
}else{
	print "<a href=../loginpage.php>$MSG_LOGIN</a>";
	print "<a href=../registerpage.php>$MSG_REGISTER</a>";
}
if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])){
	print "<a href=../admin>$MSG_ADMIN</a>";

}
?>


</div><!--end profile-->

<div id="broadcast" class="container">
	<marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';>
		<?php echo $view_marquee_msg?>
	</marquee>
</div>


