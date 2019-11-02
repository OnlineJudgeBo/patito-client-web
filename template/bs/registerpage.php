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
	<form action="register.php" method="post">
				<br><br>
				<center><table>
					<tr><td colspan="2" height="40" width="500">&nbsp;&nbsp;&nbsp;<?php echo $MSG_REG_INFO?></td></tr>
					<tr><td width=25%><?php echo $MSG_USER_ID?>:</td>
						<td width=75%><input name="user_id" size=20 type=text>*</td>
					</tr>
					<tr><td><?php echo $MSG_NICK?>:</td>
						<td><input name="nick" size="50" type=text></td>
					</tr>
					<tr><td><?php echo $MSG_PASSWORD?>:</td>
						<td><input name="password" size="20" type=password>*</td>
					</tr>
					<tr><td><?php echo $MSG_REPEAT_PASSWORD?>:</td>
						<td><input name="rptpassword" size=20 type=password>*</td>
					</tr>
					<tr><td><?php echo $MSG_SCHOOL?>:</td>
						<td><input name="school" size=30 type=text></td>
					</tr>
					<tr><td><?php echo $MSG_EMAIL?>:</td>
						<td><input name="email" size=30 type=text></td>
					</tr>
					<?php if($OJ_VCODE){?>
		<tr><td>
Se le da un problema como un A+B o contar cuantos impares hay, pares. etc <br>
segun este en la descripcion del problema, puede cambiar entre c++ y java :D  </td>
<td>
 <script type ="text/javascript" src="http://www.codecha.org/api/challenge?k=e7aaf95106e04585b9c1b623c134b074"> </script> 
</td>
					</tr>
					<?php }?>
					<tr><td></td>
						<td><input value="Registrar" name="submit" type="submit">
							&nbsp; &nbsp;
							<input value="Reiniciar" name="reset" type="reset"></td>
						</tr>
					</table></center>
					<br><br>
				</form>

			</section>
		</div>

		<section id="foot">
			<?php require_once("oj-footer.php");?>
		</section>
	</body>
	</html>


