<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-62045758-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-62045758-1');
</script>


<div id="header">
  <div id="logo"></div>
  <div id="menu">
    <ul>
     <li><a href="<?php echo $OJ_HOME?>"> <?php echo $MSG_HOME?> </a>
       <!-- <li><a href="bbs.php" ><?php echo $MSG_BBS?></a></li> -->
       <li ><a href="problemset.php"><?php echo $MSG_PROBLEMS?> </a> </li>
       <li><a  href="status.php"><?php echo $MSG_STATUS?> </a></li>
       <li><a href="ranklist.php"><?php echo $MSG_RANKLIST?></a></li>
       <li><a href="contest.php"><?php echo checkcontest($MSG_CONTEST)?></a></li>
       <!--<a class='btn <?php if ($url=="recent-contest.php") echo " $ACTIVE";?>' href="recent-contest.php"><i class="icon-share"></i><?php echo "$MSG_RECENT_CONTEST"?></a>-->
       <li ><a href="faqs.php"><?php echo "$MSG_FAQ"?></a></li>
	   <li><a href="tutoriales/index.html" target="_blank">Tutoriales</a></li>
	   <li style="text-align: center;line-height: 15px;"><a href="info.html">Carrera<br/>de Informática</a></li>
     </ul>
   </div>
   <div id="profile" >
    <script src="include/profile.php?<?php echo rand();?>" ></script></div>
    <!--end profile-->
  </div>
  <div id="broadcast" class="container">
    <marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';><?php echo $view_marquee_msg?></marquee>
  </div>
