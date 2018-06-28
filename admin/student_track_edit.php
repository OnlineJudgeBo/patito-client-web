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
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#student_track').DataTable();
		$('#student_track tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
		} );
	} );
</script>

<fieldset>
	<legend>Listado de Cursos de <?php $user =$_SESSION['user_id']; echo $user;?></legend> 
	<table id="student_track"  class="display" cellspacing="0" width="100%"> 
		<thead> 
			<tr> 
				<th>N°</th> 
				<th>Nombre</th> 
				<th># Estudiantes</th> 
				<th>Ver</th> 
				<th>Editar</th>
			</tr> 
		</thead> 
		<tbody> 

			<?php 
			$sql= "SELECT * FROM  track where user_id = '$user'";
			$result = mysql_query($sql) or die(mysql_error());
			for ($cont = 1;$row=mysql_fetch_object($result);$cont++){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$row->name."</td>";
				$sql_ = "SELECT COUNT( id_track ) FROM track_users WHERE id_track ='$row->track_id'";
				$result_ = mysql_query($sql_) or die(mysql_error());
				$row_	 = mysql_result($result_, 0);
				echo "<td>".$row_."</td>";
				echo "<td><a href=student_track_statics.php?index=".$row->track_id."&room_name=$row->name>Ver</a></td>";
				echo "<td><a href=student_track_room_edit.php?index=".$row->track_id.">Editar</a></td>";
				echo "</tr>";
			}
			?>
		</tbody> 
	</table>


