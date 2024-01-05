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
			<center>
				<table>
					<form method="post" action="blog_add.php" >
						<input type="hidden" value=<?php echo $row->blog_id;?> name="edit">
						<tr>
							<td>  Titulo :<input name="title" size="32" value=<?php echo $row->title;?>>
							</td>
						</tr>
						<tr>
							<td>
								<?php 
								require_once("fckeditor/fckeditor.php"); 
								$description = new FCKeditor('content') ;
								$description->BasePath = 'fckeditor/' ;
								$description->Height = 250 ;
								$description->Width=800;
								$description->Value = "<p> $row->content; </p>" ;
								$description->Create() ;
								?> 
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" value=<?php echo $MSG_SUBMIT?>>
							</td>
						</tr>
					</form>
				</table>
			</center> 

		</section>
	</div>
	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>
