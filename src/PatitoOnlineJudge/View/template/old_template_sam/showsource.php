<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
	<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 
	<script src='highlight/scripts/shCore.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
	<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
	<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>

	<script language='javascript'> 
		SyntaxHighlighter.config.bloggerMode = false;
		SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
		SyntaxHighlighter.all();
	</script>
</head>
<body>

	<div id="wrapper">
		<?php require_once("oj-header.php");?>


		<section id="main">
			<?php

			if ($ok==true or $_SESSION['user_id']=='starsaminf' or $_SESSION['user_id']=='jteran'){
				if($view_user_id!=$_SESSION['user_id'])
					echo "<a href='mail.php?to_user=$view_user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
				echo "<div id='result' style='display: none;'>".$judge_result[$sresult]."</div>";
				echo "<div id='time' style='display: none;'>".$stime." ms</div>";
				echo "<div id='memory' style='display: none;'>".$smemory." kb</div>";
				$brush=strtolower($language_name[$slanguage]);
				if ($brush=='pascal') $brush='delphi';
				if ($brush=='obj-c') $brush='c';
				if ($brush=='freebasic') $brush='vb';
				if ($brush=='python2' || $brush=='python3' ||  $brush=='python3.7' ) $brush='python';
				if ($brush=='C++11')   $brush='c++';

				echo "<pre class=\"brush:".$brush.";\">";
				ob_start();
				echo "/**************************************************************\n";
				echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
				echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
				if ($sresult==4){
					echo "\tTime:".$stime." ms\n";
					echo "\tMemory:".$smemory." kb\n";
				}
				echo "****************************************************************/\n\n";
				$auth=ob_get_contents();
				ob_end_clean();

				echo htmlspecialchars(str_replace("\n\r","\n",$view_source))."\n".$auth."</pre>";
				
			}else{
				
				echo "No puedes ver este codigo!";
			}
			?>

		</section>
	</div>

	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>
