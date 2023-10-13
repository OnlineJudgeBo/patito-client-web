<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $view_title?></title>
    <link rel="icon" type="image/png" href="logo.jpg" />
    <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
    <!--******-->
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <script type="text/javascript" src="js/materialize.min.js"></script>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!--******-->
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<!--    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-62045758-1', 'auto');
      ga('send', 'pageview');

    </script>
-->

 <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
            
	
  </head>
  <body>
	<?php require_once("oj-header.php");?>
	<div class="row">
	  <div class="col s9">
		<div style="border:DodgerBlue solid 1px; border-radius:10px; padding:20px; margin:10px">
                 <?php echo $view_news ?>
		  <div id="calendar">
			<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=codechef.com_3ilksfmv45aqr3at9ckm95td5g%40group.calendar.google.com&amp;color=%23B1440E&amp;src=ak75rre322mrpoascoedm4af08%40group.calendar.google.com&amp;color=%23B1365F&amp;src=hb26migjl0dktrssgdg5ocedoo%40group.calendar.google.com&amp;color=%232F6309&amp;src=raihanruhin%40gmail.com&amp;color=%231B887A&amp;src=gi183ogbbd1ar5i02bsb5u3uvakbuaqs%40import.calendar.google.com&amp;color=%23333333&amp;ctz=America%2FLa_Paz"  style="width: 100%;height:500px" frameborder="0" scrolling="no"></iframe>
		  </div>
		</div>
	  </div>
	  <div class="col s3">
		<div style="border:DodgerBlue solid 1px; border-radius:10px; padding:5px; margin:0px">
		  <?php include "contestList.php";?>
		  <!--<h1>Noticias</h1>
		  <?php echo $view_news ?>-->
		</div>
	  </div>
	</div>
	<?php require_once("oj-footer.php");?>


  <!-- Modal Structure -->
  <!--<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
      <h4>Nuevo diseño </h4><br>
      <p>Hola,<br> Estamos probando un nuevo diseño en <a href="/jv">Click</a></p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cerrar</a>
    </div>
  </div>
<script>
$(document).ready(function(){
    $('#modal1').modal();
    $('#modal1').modal('open');
});
</script>
-->
  </body>
</html>
