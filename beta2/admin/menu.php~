<?php require_once("admin-header.php");

if(isset($OJ_LANG)){
	require_once("../lang/$OJ_LANG.php");
}
?>
<html>
<head>
	<title><?php echo $MSG_ADMIN?></title>
</head>

<body>
	<hr>
	<h6>
		<ol>
			<li><a href="watch.php" target="main"><b><?php echo $MSG_SEEOJ?></b></a></li>

			<?php if (isset($_SESSION['administrator'])|| isset($_SESSION['problem_master_editor'])){?>
			<li><a href="user_list.php" target="main">EditarUsuarios </a></li>
			<li><a href="student_track_menu.php" target="main"><b><?php echo $MSG_TRACK?></b></a></li>
			<li><a href="news_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_NEWS?></b></a></li>
			<li><a href="news_list.php" target="main"><b><?php echo $MSG_NEWS.$MSG_LIST?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])||isset($_SESSION['problem_master_editor'])){?>
			<li><a  href="problem_add_page.php" target="main"><b><?php echo $MSG_ADD.$MSG_PROBLEM?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])||isset($_SESSION['problem_master_editor'])){?>
			<li><a  href="problem_list.php" target="main"><b><?php echo $MSG_PROBLEM.$MSG_LIST?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])){?>		
			<li><a  href="contest_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_CONTEST?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])){?>
			<li><a  href="contest_list.php" target="main"><b><?php echo $MSG_CONTEST.$MSG_LIST?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){?>
			<li><a  href="team_generate.php" target="main"><b><?php echo $MSG_TEAMGENERATOR?></b></a></li>
			<li><a  href="setmsg.php" target="main"><b><?php echo $MSG_SETMESSAGE?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])||isset( $_SESSION['password_setter'] )){?>
			<li><a  href="changepass.php" target="main"><b><?php echo $MSG_SETPASSWORD?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator']) || isset($_SESSION["contest_creator"])){?>
			<li><a  href="rejudge.php" target="main"><b><?php echo $MSG_REJUDGE?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){	?>
			<li><a  href="privilege_add.php" target="main"><b><?php echo $MSG_ADD.$MSG_PRIVILEGE?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){?>
			<li><a  href="privilege_list.php" target="main"><b><?php echo $MSG_PRIVILEGE.$MSG_LIST?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){?>
			<li><a  href="source_give.php" target="main"><b><?php echo $MSG_GIVESOURCE?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){	?>
			<li><a  href="problem_export.php" target="main"><b><?php echo $MSG_EXPORT.$MSG_PROBLEM?></b></a></li>
			<?php }

			if (isset($_SESSION['administrator'])){?>
			<li><a  href="problem_import.php" target="main"><b><?php echo $MSG_IMPORT.$MSG_PROBLEM?></b></a></li>
			<?php }

			if (isset($_SESSION['master_administrator']) || isset($_SESSION['starsaminf'])){ ?>
			<li><a  href="update_db.php" target="main"><b><?php echo $MSG_UPDATE_DATABASE?></b></a></li>
			<?php }

			if (isset($OJ_ONLINE)&&$OJ_ONLINE && isset($_SESSION['administrator']) ){?>
			<li><a  href="../online.php" target="main"><b><?php echo $MSG_ONLINE?></b></a></li>
			<?php }	?>
			<li><a  href="http://code.google.com/p/freeproblemset/" target="_blank"><b>FreeProblemSet</b></a></li>
		</ol>
		<?php if (isset($_SESSION['administrator'])){?>
		<a href="problem_copy.php" target="main" title="Create your own data"><font color="eeeeee">CopyProblem</font></a> <br>
		<a href="problem_changeid.php" target="main" title="Danger,Use it on your own risk"><font color="eeeeee">ReOrderProblem</font></a>
		<?php }	?>
		<h4>
		</body>
		</html>
