<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>

	<div id="wrapper">

		<?php require_once("oj-header.php");?>
		<section id="main">
			<?php echo $view_blog?>
		</section>
		<section id="comment">
			<?php echo $view_comment?>
			
		</section>	
		<form method="post" >
			<?php echo "<div onclick='comment_add()'><h1>Agregar comentario </h1></div>" ?>
			<div id="add_comment" ></div>

		</form>	


		
	</div>
	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>
<script type="text/javascript">
	<?php 
	require_once("fckeditor/fckeditor.php"); 
	$description = new FCKeditor('comment_txt') ;
	$description->BasePath = 'fckeditor/' ;
	$description->Height = 250 ;
	$description->Width=800;
	$description->Value = "";
	?> 
	var sw=1;
	function comment_hidden(){
		document.getElementById('add_comment').innerHTML="";
	}
	function comment_add(){
		sw=!sw;
		if(sw)comment_hidden();
		else{	   
			//var n='<?php  $description->Create() ?><br><input type="submit" value="Enviar">';
			var n='<textarea name="jalar"></textarea><br><input type="submit" value="Enviar">';
			document.getElementById('add_comment').innerHTML=n;
		}
	}

</script>