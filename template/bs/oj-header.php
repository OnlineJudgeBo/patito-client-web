<div id="header">
<div id="logo">
     <div id="logo_text">
          <h1 id="logo_colour">Bienvenido al Juez de la Carrera de Informatica - UMSA</h1>
          <h2>Un humilde juez de practicas BETA</h2>
     </div>
</div>


	 <ul id="button" >	 	
	  <?php $ACTIVE="btn-warning";?>
	  <li  id="home" class>
		<a  href="<?php echo $OJ_HOME?>" ><i class="icon-home"></i>
		<?php echo $MSG_HOME?>						
		</a>
	  </li>
	 <!-- <li id="bss" >
		<a  href="bbs.php" >
		<i class="icon-comment"></i><?php echo $MSG_BBS?></a>
	  </li>-->

		<li id="problemset" >
		<a href="problemset.php">
		<i class="icon-question-sign"></i><?php echo $MSG_PROBLEMS?></a>
		</li>

		<li id="submitpage" >
	   <a href="submitpage.php">
	   <i class="icon-pencil"></i><?php echo "submitpage"?></a>
		</li>

		<li id="status" >
		<a  href="status.php">
		<i class="icon-check"></i><?php echo $MSG_STATUS?></a>
		</li>

		<li id="ranklist" >
		<a href="ranklist.php">
		<i class="icon-signal"></i><?php echo $MSG_RANKLIST?></a>
		</li>

		<li id="constest" >	
		<a href="contest.php">
		<i class="icon-fire"></i><?php echo checkcontest($MSG_CONTEST)?></a>
		</li>
		
		<!--<a class='btn <?php if ($url=="recent-contest.php") echo " $ACTIVE";?>' href="recent-contest.php">
		<i class="icon-share"></i><?php echo "$MSG_RECENT_CONTEST"?></a>
		
		<a class='btn <?php if ($url==(isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php")) echo " $ACTIVE";?>' href="<?php echo isset($OJ_FAQ_LINK)?$OJ_FAQ_LINK:"faqs.php"?>">
                <i class="icon-info-sign"></i><?php echo "$MSG_FAQ"?></a>
		-->
	 </ul>


<div id="profile" >
<script src="include/profile.php?<?php echo rand();?>" ></script>
</div><!--end profile-->

	
</div>
<div id="broadcast" class="container">
<marquee id="broadcast" scrollamount="1" behavior="alternate" scrolldelay="1" onMouseOver='this.stop()' onMouseOut='this.start()';>
  <?php echo $view_marquee_msg?>
</marquee>
</div>

