<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css">
<?php 
require("admin-header.php");
require_once("../include/my_func.inc.php");
require_once("../include/set_get_key.php");

if (!(isset($_SESSION['administrator'])||isset($_SESSION['problem_master_editor']))){
		echo "<a href='/../../loginpage.php'>Please Login First!</a>";
			exit(1);
}
?>
<?php 
if(isset($_POST['user_name'])&&isset($_POST['user_email'])){
		if (!is_valid_user_name($_POST['user_name'])){
					echo "Error nombre";
							exit(1);
						}
			$user_id    =mysql_real_escape_string(htmlspecialchars ($_POST['user_id']));
			$user_name  =mysql_real_escape_string(htmlspecialchars ($_POST['user_name']));
				$user_email =mysql_real_escape_string(htmlspecialchars ($_POST['user_email']));
				$lastname   =mysql_real_escape_string(htmlspecialchars ($_POST['lastname']));

					$sql= "UPDATE users SET nick='$user_name', email='$user_email', lastname = '$lastname' where user_id = '$user_id'";
					$result = mysql_query($sql) or die(mysql_error());
						Header("Location:user_list.php");
}
?>
<?php 
if(isset($_GET['user_id'])){
		$user = $_GET['user_id'];
			$sql = "select * from users where user_id='$user'";
			$result = mysql_query($sql) or die(mysql_error());
				$row=mysql_fetch_object($result);
}else{
		echo "<div id='error'>error</div>";
			exit(1);
}
?>
<form action="#" method="POST">
	<fieldset>
		<legend>Edicion</legend>
		<section id="main">
			<label>	Usuario ID   :</label><input type="text"  name="user_id"    value="<?php echo $row->user_id?>" readonly="readonly"></br>
			<label>	Nombre       :</label><input type="text"  name="user_name"  value="<?php echo $row->nick?>" required></br>
			<label>	Apellidos    :</label><input type="text"  name="lastname"   value="<?php echo $row->lastname?>" required></br>
			<label>	Email        :</label><input type="email" name="user_email" value="<?php echo $row->email?>" required></br>
			
		</section>
		<button> Guardar </button>
	</fieldset>
</form>
<label>Cambiar clave de usuario</label> <a href="changepass.php">Cambiar</a>
