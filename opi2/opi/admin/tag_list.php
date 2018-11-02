<?php
$vector = array(
	"ad" =>array("Binarios","Cadenas","Clasico","Combinatoria",
		"Contar","Evaluar Expresiones","Fibonacci","For / While",
		"Formato","Horas","Juegos","Matrices","Maximo Minimo",
		"Minima Suma","Ordenar","Clasificacion","Primos","Vectores",
		"Palindrome","Anagrama"),

	"pd" =>array("Clasico"),

	"ma" =>array("Formula","Logaritmo","Cambio de Base","Teoria de Juegos",
		"Java BigInteger ","Primos","GCD o LCM","Factoria","Factores Primos",
		"Criba","Aritmetica Modular"),

	"st"=>array("Clasico","Codifica/Decodificar","Conteo de Frecuencia",
		"Arbol de Sufijos","Comparacion de Cadena"),

	"gr"=>array("Clasico","Orden Topologico","Grafo Bipartito","Arbol",
		"MST","fKUJO","Grafo ESpecial"),
	"es"=>array("Pilas","Colas","Listas","Arboles","Arbol Binario","Arbol de Segmentos","BIT","Has Map","Tree Map",
                "Union Find")

	);



function forall($id){
	global $vector;
	echo "<select  multiple='multiple' id='seltag' name='seltag[]' size='8'>";
	for($i=0;$i<count($vector[$id]);$i++){
		echo "<option value='".$vector[$id][$i]."'>";
		echo $vector[$id][$i];
		echo "</option>";
	}
	echo "</select>";
}
/*
	Marca todos los usados en el tag2
*/
	function get_all($id,$list){
		global $vector;
		$list=explode(", ", $list);
		echo '<script type="text/javascript">';
		echo "$(document).ready(function(){";
			$j=0;
			for($i=0;$i<count($vector[$id]);$i++){
				if($list[$j]==$vector[$id][$i]){
					echo '$("#seltag > option[value=';
						echo "'";
						echo $vector[$id][$i];
						echo "'";
						echo "]";
						echo '"';
						echo ").attr('selected', true);";
$j++;
}
}
echo "});</script>";
}

$tag=$_POST['tag'];
$marc=$_POST['marc'];
$list = $_POST['list'];
if($tag=="ad" or $tag=="pd" or $tag=="st" or $tag=="ma" or $tag=="gr" or $tag=="at" or $tag=="es"){
	forall($tag);
	if($marc){
		get_all($tag,$list);
	}
}else{
	echo "<select  multiple='multiple' id='seltag' name='seltag[]' size='8'>";
	echo "<option> No hay sub clasificacion </option>";
	echo "</select>";
}

?>


