<?php
require_once("./include/db_info.inc.php");

if(!empty($_POST["key"])) {

	$query ="SELECT * FROM institucion where id_pais = ".$_POST["key"]." or id_pais = 0";
	$result = mysql_query($query);

	if(!empty($result)) {
		$retorno = "<select id=institucion_uni name=institucion_uni>";
		$sel = " selected";
		while($row = mysql_fetch_object($result)){
			$retorno .="<option value='".$row->id_institucion."'".$sel." >".($row->nombre)."</option>";
			$sel = "";
		}
		echo $retorno."</select>";
	}	
}
?>