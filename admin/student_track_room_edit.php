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
/*
Elimina a un usuario de la clase x
*/
if(isset($_GET['eliminar']) && isset($_GET['index'])){
	if(!is_valid_user_name($_GET['eliminar'])){
		echo "<div id=error>Error solo letras y numeros</div>";
		exit(1);
	}
	$inde = intval($_GET['eliminar']);
	$sql= "DELETE FROM track_users where id = '$inde'";
	$result = mysql_query($sql) or die(mysql_error());
}
if(isset($_POST['name_room']) && isset($_GET['index'])){
	if(!is_valid_user_name($_POST['name_room'])){
		echo "<div id=error>Error solo letras y numeros</div>";
		exit(1);
	}
	$inde = intval($_GET['index']);
	$name = $_POST['name_room'];
	$sql= "UPDATE track SET name='$name' where track_id = '$inde'";
	$result = mysql_query($sql) or die(mysql_error());
	
}
if(isset($_POST['student_list'])&& isset($_GET['index'])){
	$inde = intval($_GET['index']);
	$vec = explode(",",$_POST['student_list']);
	for($i = 0;$i<sizeof($vec);$i++){
		$sql    ="INSERT INTO track_users (id_track,user_id)VALUES($inde,'$vec[$i]')";
		$result = mysql_query($sql) or die(mysql_error());
	}
}
$user = $_SESSION['user_id'];
$index = intval($_GET['index']);

$sql= "SELECT * FROM  track where user_id = '$user' and track_id='$index'";
$result = mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
?>
<fieldset>
	<legend>Marque a los usuarios q desee eliminar. No hay vuelta a atras</legend>
	<fieldset> 
		<legend>Editar curso </legend>
		<form action="#" method="post" >
			Nombre de Clase <input type=text name="name_room"value=<?php echo $row->name ?>>
			<button> Guardar</button>
		</form>
	</fieldset>
	Lista Estudiantes Registrados<br> 
	<table id="student_track"  class="display" cellspacing="0" width="100%"> 
		<thead> 
			<tr> 
				<th>N°</th> 
				<th>Nombre</th> 
				<th>Usuario</th> 
				<th>Eliminar</th>
			</tr> 
		</thead> 
		<tbody> 
			<?php 	
			$sql = "SELECT track.id, user.user_id, user.nick
			FROM track_users AS track, users user
			WHERE track.id_track =  '$index'
			AND track.user_id = user.user_id";

			$result = mysql_query($sql) or die(mysql_error());
			for ($cont = 1;$row=mysql_fetch_object($result);$cont++){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$row->nick."</td>";
				echo "<td>".$row->user_id."</td>";
				echo "<td> <a href=student_track_room_edit.php?index=$index&eliminar=$row->id>Eliminar</a></td>";
				echo "</tr>";
			}
			?>
		</tbody> 
	</table>
	<fieldset>
		<legend>Agregar nuevos Estudiantes</legend>
		<?php
		$sql 	= 'SELECT user_id, email, nick, school FROM  users';
		$result = mysql_query($sql) or die(mysql_error());
		?>
		<table id="student_track2"  class="display" cellspacing="0" width="100%"> 
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

		<form action="student_track_room_edit.php?index=<?php echo $index?>" method="POST">
			<input type="hidden" id ="student_list" name = "student_list" >
			<button id ="button_agregar"> Agregar </button>
		</form>
	</fieldset>
</fieldset>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#student_track').DataTable();
		$('#student_track tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
		} );

		
		var table2 = $('#student_track2').DataTable();
		$('#student_track2  tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
		} );

		$('#button_agregar').click( function () {
			for(var i=0;i<table2.rows('.selected').data().length;i++){
				var dato = table2.rows('.selected').data()[i];

				if (i+1 == table2.rows('.selected').data().length){
					$('#student_list').val($('#student_list').val()+dato[2]);
				}else{
					$('#student_list').val($('#student_list').val()+dato[2]+",");
				}
			}
		} );
	} );
</script>