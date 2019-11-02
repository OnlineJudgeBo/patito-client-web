<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>
	
<pre id='errtxt' class="alert alert-error"><?php echo $view_reinfo?></pre>
<div id='errexp'>Explain:</div>

<script>
   var pats=new Array();
   var exps=new Array();
   pats[0]=/A Not allowed system call.* /;
   exps[0]="Desbordaste el stack, te saliste de los límites de un arreglo, etc. <br> Vuelve a leer el problema y piensa qué casos se te olvió considerar <br> Acceso no autorizado a los recursos tales como archivos o procesos";
   pats[1]=/Segmentation fault/;
   exps[1]="límites de matriz comprobar si excepciones de puntero, el acceso a áreas de memoria no deben ser accesibles";
   pats[2]=/Floating point exception/;
   exps[2]="Flotante error de punto, compruebe si se ha dividido por cero...";
   pats[3]=/buffer overflow detected/;
   exps[3]="Los desbordamientos del búfer, comprobar si se ha producido una gran variedad de longitud de la cadena superado";
   pats[4]=/Killed/;
   exps[4]="Proceso debido a las razones de la memoria o el tiempo para matar, para comprobar si existe un bucle infinito";
   pats[5]=/Alarm clock/;
   exps[5]="Proceso por el tiempo de la razón fue asesinado, compruebe si hay un bucle infinito, este error es equivalente a un tiempo de espera de TLE";
   
  
   
   function explain(){
     //alert("asdf");
       var errmsg=document.getElementById("errtxt").innerHTML;
	   var expmsg="Explicacion ：<br>";
	   for(var i=0;i<pats.length;i++){
		   var pat=pats[i];
		   var exp=exps[i];
		   var ret=pat.exec(errmsg);
		   if(ret){
		      expmsg+=ret+":"+exp+"<br>";
		   }
	   }
	   document.getElementById("errexp").innerHTML=expmsg;
     //alert(expmsg);
   }
   explain();
 
 </script>
 
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>

