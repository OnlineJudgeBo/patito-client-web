<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $view_title?></title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/template/og/css/materialize.min.css"  media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  </head>
  <body class="orange lighten-5 orange-text text-darken-4" onload="cargar()">        
    <!-- HEADER-->
    <?php require_once("oj-headerM.php");?>
    <center>
        <form action="login.php" method="post">
	  <table style="width:80%"><tr><td>
	  <input id="password" name="user_id" type="text" class="validate">
	  <label for="password"><?php echo $MSG_USER_ID?></label>
	  <input id="password" name="password" type="password" class="validate">
	  <label for="password">Password</label>
	  </td><td>
          <input name="submit" type="submit" size="10" value="Ingresar" class='btn waves-effect orange accent-4' style="padding: 15 10 15 10; height:100px;">
	  </td></tr></table>
        </form>
	</br>
	<a href="lostpassword.php" class='btn waves-effect orange accent-4'>Recuperar contraseña</a></br></br>
	<a href=./registerpage.php class='btn waves-effect orange accent-4'>¿No tienes usuario?</a></br></br>
    </center>
    <!-- FOOTER -->
    <?php require_once("oj-footerM.php");?>
  </body>
</html>
