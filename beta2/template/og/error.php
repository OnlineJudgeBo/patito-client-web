<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     <title><?php if(isset($view_title))echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

</head>
<body>

	<div id="wrapper">
		<?php require_once("oj-header.php");?>
		<section id="main">
			<?php echo $view_errors?>

		</section>
	</div>

	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>





