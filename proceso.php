<?php
	  require_once('./include/db_info.inc.php');
		$con=mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
		// Check connection
		if (mysqli_connect_errno())
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$sql="INSERT INTO mensajes (usuario, contenido) VALUES ('".$_SESSION['user_id']."','".mysql_escape_string($_POST['contenido'])."')";

		if (!mysqli_query($con,$sql)){
		  die('Error: ' . mysqli_error($con));
		}

		//respuesta 
		$result = mysqli_query($con,"SELECT *
from (SELECT * FROM `mensajes` ORDER BY `mensajes`.`id` DESC  limit 20) aux
ORDER by `id`");
		while($row = mysqli_fetch_array($result))
		{
		  echo "<b>".$row['usuario']."</b>: ".htmlentities($row['contenido']);
		  echo "<hr>";
		} 
		 
		mysqli_close($con);
?> 