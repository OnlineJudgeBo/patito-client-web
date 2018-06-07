
<?php

$usuario = $_POST['usuario'];
$clave   = $_POST['clave'];

if($usuario == "user" && $clave == "clave"){
	echo "TOO OK";
}else{
echo "Error";
}
