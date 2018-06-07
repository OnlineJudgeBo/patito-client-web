<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel="icon" type="image/png" href="logo.jpg" />
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62045758-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>
  <div id="wrapper">
   <?php require_once("oj-header.php");?>
    <section id="sidebar">
      <!--list contest -->
      <div>
        <?php include "contestList.php";?>
      </div>
      <!-- end list contest-->
      <h1>Noticias</h1>
      <?php echo $view_news ?>
    </section>
    <section id="main_main">
   <!--   <div id="calendar">
        <h1> Calendario de eventos</h1>
        <iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=codechef.com_3ilksfmv45aqr3at9ckm95td5g%40group.calendar.google.com&amp;color=%23B1440E&amp;src=ak75rre322mrpoascoedm4af08%40group.calendar.google.com&amp;color=%23B1365F&amp;src=hb26migjl0dktrssgdg5ocedoo%40group.calendar.google.com&amp;color=%232F6309&amp;src=raihanruhin%40gmail.com&amp;color=%231B887A&amp;src=gi183ogbbd1ar5i02bsb5u3uvakbuaqs%40import.calendar.google.com&amp;color=%23333333&amp;ctz=America%2FLa_Paz"  style="width: 100%;height:500px" frameborder="0" scrolling="no"></iframe> 
      </div>
--!>
<img src="grupos.png" />
      <br>
      <a href="blog_add.php"> Agregar entrada al blog </a>
      
    </section><!--end main-->
    <div id="blog">
      <?php echo $view_blog ?>
    </div>
  </div><!--end wrapper-->
  <section id="foot">
    <?php require_once("oj-footer.php");?>
  </section><!--end foot-->
</body>
</html>
