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
        <div id="login_img"></div>
        <form action="login.php" method="post">
           <table width="480" algin="center">
            <tr>
                <td width="240"><?php echo $MSG_USER_ID?>:</td>
                <td width="200">
                    <input style="height:24px" name="user_id" type="text" size="20">
                </td>
            </tr>
            <tr>
                <td><?php echo $MSG_PASSWORD?>:</td>
                <td>
                    <input name="password" type="password" size="20" style="height:24px">
                </td>
                
                
            </tr>
            <tr>
                <td colspan="3">
                    <input name="submit" type="submit" size="10" value="Submit">
                    <a href="lostpassword.php">Recuperar contraseña</a>
                </td>
            </tr>
        </table>
    </form>
</section>
</div>

<section id="foot">
    <?php require_once("oj-footer.php");?>
</section>
</body>
</html>
