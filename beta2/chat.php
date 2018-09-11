<?php 
	
	if ($_POST) {

   		if ($_POST['ajax']=='mandar') {
   			//echo $_POST['contenido'];
   			require_once('proceso.php');
   		}else{
   			require_once('mensajes.php');
   		}
   }else{
   		
   }
?>


