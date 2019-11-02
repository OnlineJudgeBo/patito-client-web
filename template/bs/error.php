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
			<?php echo $view_errors?>

		</section>
	</div>

	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>





