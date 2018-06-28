<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css">
<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator'])||isset($_SESSION['problem_master_editor']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
?>
<?php 
if(isset($_POST['name_room']) && isset($_POST['student_list']) && strlen($_POST['student_list']) > 0){

	$name_room=mysql_real_escape_string(htmlspecialchars ($_POST['name_room']));
	$student_list=mysql_real_escape_string(htmlspecialchars ($_POST['student_list']));
	
	$vec = explode(",",$student_list);
	$fecha = date('Y-m-d H:i:s', time());
	$user =$_SESSION['user_id'];
	$sql = "INSERT INTO track (name,user_id,time_stamp) VALUES('$name_room','$user','$fecha')";
	
	$result = mysql_query($sql) or die(mysql_error());
	$sql=  "Select track_id from track order by track_id DESC LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	$id 	= mysql_fetch_object($result);

	for($i = 0;$i<sizeof($vec);$i++){
		if(!is_valid_user_name($vec[$i])){
			echo "<div id=error>Error user_id solo letras y numeros</div>";
			exit(1);
		}
		$sql    ="INSERT INTO track_users (id_track,user_id)VALUES($id->track_id,'$vec[$i]')";
		$result = mysql_query($sql) or die(mysql_error());
	}
	header('Location:student_track_list.php');
}else{
	echo "<div id=error>Ingrese un nombre para la clase y seleccione a los integrantes</div>";
}
?>


<fieldset>
	<legend>Crear una nuevo curso</legend>
	<form id="sudent_track" method="POST" action="#">
		Nombre de la clase :<input type="text" name="name_room"><br>
		<input type="hidden" name="student_list" id="student_list">
		<button>Crear</button> 
	</form> 
	<hr>
	<h2>Seleccione a sus Alumnos</h2>
	<?php
	$sql 	= 'SELECT user_id, email, nick, school FROM  users';
	$result = mysql_query($sql) or die(mysql_error());
	?>
	<table id="student_track"  class="display" cellspacing="0" width="100%"> 
		<thead> 
			<tr> 
				<th>N°</th> 
				<th>Nombre</th> 
				<th>Usuario</th> 
				<th>Email</th> 
				<th>School</th> 
			</tr> 
		</thead> 
		<tbody> 
			<?php 
			for ($cont = 1;$row=mysql_fetch_object($result);$cont++){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$row->nick."</td>";
				echo "<td>".$row->user_id."</td>";
				echo "<td>".$row->email."</td>";
				echo "<td>".$row->school."</td>";
				echo "</tr>";
			}
			?>
		</tbody> 
	</table>
	
</fieldset>


<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#student_track').DataTable();
		$('#student_track tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
		} );

		$('button').click( function () {
			for(var i=0;i<table.rows('.selected').data().length;i++){
				var dato = table.rows('.selected').data()[i];

				if (i+1 == table.rows('.selected').data().length){
					$('#student_list').val($('#student_list').val()+dato[2]);
				}else{
					$('#student_list').val($('#student_list').val()+dato[2]+",");
				}
			}
		} );

	} );
</script>