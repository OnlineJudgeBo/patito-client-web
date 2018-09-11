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

			if (isset($OJ_ONLINE)&&$OJ_ONLINE && isset($_SESSION['administrator']) ){?>
			<li><a  href="../online.php" target="main"><b><?php echo $MSG_ONLINE?></b></a></li>
			<?php }	?>
		</ol>
		</body>
		</html>
