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
			<form method="post" action="register.php" id="formulario" onsubmit="return captcha();">
				<br><br>
				<center><table>
					<tr><td colspan="2" height="40" width="500">&nbsp;&nbsp;&nbsp;<?php echo $MSG_REG_INFO?></td></tr>
					<tr><td width=25%><?php echo $MSG_USER_ID?>:</td>
						<td width=75%><input name="user_id" size=20 type="text" required>*</td>
					</tr>
					<tr><td><?php echo $MSG_NICK?>:</td>
						<td><input name="nick" size="50" type="text" required>*</td>
					</tr>
					<tr><td><?php echo $MSG_PASSWORD?>:</td>
						<td><input name="password" size="20" type="password" required>*</td>
					</tr>
					<tr><td><?php echo $MSG_REPEAT_PASSWORD?>:</td>
						<td><input name="rptpassword" size=20 type="password" required>*</td>
					</tr>
					<tr><td><?php echo $MSG_SCHOOL?>:</td>
						<td><input name="school" size=30 type=text></td>
					</tr>
					<tr><td><?php echo $MSG_EMAIL?>:</td>
						<td><input name="email" size=30 type="email" required>*</td>
					</tr>
					<?php if($OJ_VCODE){?>
					<tr><td></td>
						<div id="csscapt" style="margin-top: 13%;float: right;">	  
							<?php	require_once("include/recaptcha.php");
							$publickey = "6Lck5fsSAAAAACzXdscia6ygFYYwVyev0xllRWjA";
							$cap= recaptcha_get_html($publickey);
							echo $cap;
							?>
						</div>
					</tr>
					<?php  }?>
				</table>
				<table>
					<tr>
						<td><input value="Registrar" name="submit" type="submit">
							&nbsp; &nbsp;
							<input value="Reiniciar" name="reset" type="reset"></td>
						</tr>
					</table>

				</center>
				<br><br>
			</form>

		</section>
	</div>

	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
<script type="text/javascript">
	function captcha(){
		var formulario=document.getElementById("formulario");
		if(document.getElementById("recaptcha_response_field").value==""){
			alert("Escriba el texto debajo la imagen");
			return false;
		}else{
			formulario.submit();
			return true;
		}
	}
</script>
</html>


