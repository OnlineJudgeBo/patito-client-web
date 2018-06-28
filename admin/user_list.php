<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css">
<?php require("admin-header.php");
require_once("../include/set_get_key.php");

if (!(isset($_SESSION['administrator'])||isset($_SESSION['problem_master_editor']))){
		echo "<a href='/../../loginpage.php'>Please Login First!</a>";
			exit(1);
}
?>
<fieldset>
	<legend>Lista de usuarios</legend>
	<?php
	$sql 	= 'SELECT user_id, email, nick, lastname FROM  users';
	$result = mysql_query($sql) or die(mysql_error());
	?>
	<table id="student_track"  class="display" cellspacing="0" width="100%"> 
		<thead> 
			<tr> 
				<th>N°</th> 
				<th>Usuario</th> 
				<th>Nombre</th> 
				<th>Apellidos</th>
				<th>Email</th> 
				<th>Editar</th> 
			</tr> 
		</thead> 
		<tbody> 
			<?php 
			for ($cont = 1;$row=mysql_fetch_object($result);$cont++){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$row->user_id."</td>";
				echo "<td>".$row->nick."</td>";
				echo "<td>".$row->lastname."</td>";
				echo "<td>".$row->email."</td>";
				echo "<td> <a href=user_edit.php?user_id=$row->user_id>Editar</a></td>";
				echo "</tr>";
			}
			?>
		</tbody> 
	</table>
</fieldset>
	<script type="text/javascript">
		$(document).ready(function() {
					var table = $('#student_track').DataTable();
						} );
	</script>
