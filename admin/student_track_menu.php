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
<fieldset>
	<legend>Reporte de Usuarios beta </legend>
	<ul>
		
		<li><a href="student_track.php">Crear Nuevo Curso </a></li>
		<li><a href="student_track_edit.php "> Editar Curso </a></li>
		<li><a href="student_track_list.php">Ver Mis Cursos </a></li>
	</ul>
</fieldset>