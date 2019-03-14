<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="<?php echo $OJ_HOME?>"> <?php echo $MSG_HOME?> </a>
  <a class="navbar-brand" href="problemset.php"><?php echo $MSG_PROBLEMS?> </a>
  <a class="navbar-brand" href="status.php"><?php echo $MSG_STATUS?> </a>
  <a class="navbar-brand" href="ranklist.php"><?php echo $MSG_RANKLIST?></a>
  <a class="navbar-brand" href="contest.php"><?php echo checkcontest($MSG_CONTEST)?></a>
  <a href="faqs.php"><?php echo "$MSG_FAQ"?></a>
  <div id="profile" >
    <script src="include/profile.php?<?php echo rand();?>" ></script></div>
  <!--end profile-->
</div>
</nav>
<!--<div id="header">
  <div id="logo"></div>
  <div id="menu">
    <ul>
      <li><a href="<?php echo $OJ_HOME?>"> <?php echo $MSG_HOME?> </a>
      <li ><a href="problemset.php"><?php echo $MSG_PROBLEMS?> </a> </li>
      <li><a  href="status.php"><?php echo $MSG_STATUS?> </a></li>
      <li><a href="ranklist.php"><?php echo $MSG_RANKLIST?></a></li>
      <li><a href="contest.php"><?php echo checkcontest($MSG_CONTEST)?></a></li>
      <li ><a href="faqs.php"><?php echo "$MSG_FAQ"?></a></li>
    </ul>
  </div>
  <div id="profile" >
    <script src="include/profile.php?<?php echo rand();?>" ></script></div>
</div>
<div id="broadcast" class="container">
  <marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';><?php echo $view_marquee_msg?></marquee>
</div>
-->
