<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $view_title?></title>
    <script type="text/javascript"
	    src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
      MathJax.Hub.Config({
      tex2jax: {
      inlineMath: [['$','$'], ['\\(','\\)']],
      processEscapes: true
      }
      });
    </script>
                
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>   

    <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
    <link rel="next" href="submitpage.php?
			   <?php
			      if ($pr_flag){
			      echo "id=$id";
			      }else{
			      echo "cid=$cid&pid=$pid&langmask=$langmask";
			      }
			      ?>">
    
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <!--JavaScript at end of body for optimized loading-->
      <script type="text/javascript" src="js/materialize.min.js"></script>

    <div id="wrapper">
       
       <?php require_once("oj-header.php");?>
      <section id="main">

	<?php
	   if ($pr_flag){
	   echo "<title>$MSG_PROBLEM $row->problem_id. -- $row->title</title>";
	   echo "<center><h2>$id: $row->title</h2>";

	   }else{
	   $PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	   echo "<title>$MSG_PROBLEM $PID[$pid]: $row->title </title>";
	   echo "<center><h2>$MSG_PROBLEM $PID[$pid]: $row->title</h2>";
	   }
	   echo "<span class=green>$MSG_Time_Limit: </span>$row->time_limit Sec&nbsp;&nbsp;";
	   echo "<span class=green>$MSG_Memory_Limit: </span>".$row->memory_limit." MB";
	if ($row->spj) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
	echo "<br><span class=green>$MSG_SUBMIT: </span>".$row->submit."&nbsp;&nbsp;";
	echo "<span class=green>$MSG_SOVLED: </span>".$row->accepted."<br>"; 
	
	if ($pr_flag){
		    echo "[<a href='submitpage.php?id=$id'>$MSG_SUBMIT</a>]";
	#echo "<a class='waves-effect waves-light btn'>button</a>";
		}else{
		    echo "[<a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'>$MSG_SUBMIT</a>]";
		}
		echo "[<a href='problemstatus.php?id=".$row->problem_id."'>$MSG_STATUS</a>]";
		echo "[<a href='bbs.php?pid=".$row->problem_id."$ucid'>$MSG_BBS</a>]";
		if(isset($_SESSION['administrator']) ||isset($_SESSION['problem_master_editor'])){
		    require_once("include/set_get_key.php");
		?>
		    [<a href="admin/problem_edit.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>" >Edit</a>]
		    [<a href="admin/quixplorer/index.php?action=list&dir=<?php echo $row->problem_id?>&order=name&srt=yes" >TestData</a>]
		<?php

		}

		echo "</center>";

		echo "<h2>$MSG_Description</h2><div class=content>".$row->description."</div>";
		echo "<h2>$MSG_Input</h2><div class=content>".$row->input."</div>";
		echo "<h2>$MSG_Output</h2><div class=content>".$row->output."</div>";

		$sinput=str_replace("<","&lt;",$row->sample_input);
		$sinput=str_replace(">","&gt;",$sinput);
		$soutput=str_replace("<","&lt;",$row->sample_output);
		$soutput=str_replace(">","&gt;",$soutput);
		
		echo "<table width='100%'> <tr> <td>";
		if($sinput) {

		    echo "<h2>$MSG_Sample_Input</h2> <pre class=content><span class=sampledata>".($sinput)."</span></pre>";
		}
		echo "</td> <td>";
		if($soutput){
		    echo "<h2>$MSG_Sample_Output</h2> <pre class=content><span class=sampledata>".($soutput)."</span></pre>";
		}
		echo "</td> </tr> </table>";
		if ($pr_flag || true )
		    echo "<h2>$MSG_HINT</h2> <div class=content><p>".$row->hint."</p></div>";
		if ($pr_flag)
		    echo "<h2>$MSG_Source</h2> <div class=content><p><a href='problemset.php?search=$row->source'>".nl2br($row->source)."</a></p></div>";
		?>
	    </section>
	</div>
	<section id="foot">
	    <?php require_once("oj-footer.php");?>
	</section><!--end foot-->
    </body>
</html>
