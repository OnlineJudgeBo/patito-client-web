<?php
function checkmail(){
	 $sql="SELECT count(1) FROM `mail` WHERE
	 new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
	 $result=mysql_query($sql);
	 if(!$result) return 0;
	 $row=mysql_fetch_row($result);
	 $retmsg=$row[0];
	 mysql_free_result($result);
	 return $retmsg;
}
?>
<!--<?php
$profile="";
$lista="";
$sw=1;
if (isset($_SESSION['user_id'])){
  $sid=$_SESSION['user_id']    ;
  $lista.="<li><a href='./modifypage.php'>$MSG_USERINFO</a></li>";
  $lista.="<li><a href='./userinfo.php?user=$sid'>$sid</a></li>";
  $mail=checkmail();
  $lista.="<li><a href='./mail.php'><i class='material-icons left'>mail</i>$mail</a></li>";
  $lista.="<li><a href='./status.php?user_id=$sid'>Reciente</a></li>";
  $lista.="<li><a href='./logout.php'>$MSG_LOGOUT</a></li>";
}
if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])||isset($_SESSION['problem_master_editor'])){
   $lista.="<li><a href='./admin/'>$MSG_ADMIN</a></li>";
}
?>
-->

<?php
#require_once("pantalla.php");
#echo "Resolucion de pantalla Ancho:".$_SESSION['PantallaAncho'].'<br>';
#echo "Resolucion de pantalla Alto.:".$_SESSION['PantallaAlto'].'<br>';
#$mobiles = array("iPhone","iPod", "Android", "J2ME", "BlackBerry", "IPad", "Opera Mini", "IEMobile", "Mobile", "Windows Phone", "windows mobile", "windows ce", "webOS", "palm", "bada", "series60", "nokia", "symbian", "HTC");  
#if( $_SESSION['PantallaAncho'] < 500 ){
#  $MSG_PROBLEMS="<i class='material-icons'>apps</i>";
#  #$MSG_STATUS
#}  
?>

<script>
  ancho=screen.width; //var alto=screen.height;
  m = false;
  function cargar(){
    if(ancho < 500)
      document.getElementById("SHORTSCREEN").style.display="block";
    else
      document.getElementById("LONGSCREEN").style.display="block";
  }
  function mas(){
    var ancho=screen.width;
    if(m){
      if(ancho<500) document.getElementById("perfilSS").style.display= 'none';
      else document.getElementById("perfiSLS").style.display= 'none';
      m = false;
    }else{
      if(ancho<500) document.getElementById("perfilSS").style.display= 'block';
      else document.getElementById("perfilLS").style.display= 'block';
      m = true;
    }
  }
</script>


<div id="LONGSCREEN" style="display:none">
<div class='navbar-fixed'>
<nav>
  <div class='nav-wrapper  orange darken-4'>
    <a href='<?php echo $OJ_HOME?>'> 
      <img  height='100'  src='template/og/image/juez-patito2.svg'>    
    </a>
    <ul id='nav-mobile' class='right'>
      <li><a href=problemset.php> <?php echo $MSG_PROBLEMS?> </a></li>
      <li><a href=status.php> <?php echo $MSG_STATUS?> </a></li>
      <li><a href=ranklist.php> <?php echo $MSG_RANKLIST?> </a></li>
      <li><a href=contest.php><?php echo $MSG_CONTEST?></a></li>      
      <li><a href=faqs.php><?php echo $MSG_FAQ?></a></li>
      <?php
      if (isset($_SESSION['user_id'])){
        echo '<li><a href=./userinfo.php?user='.$sid.'>'.$sid.'</a></li>';
	$mail = checkmail();
        if ($mail>0) echo "<li><div><a href=./mail.php><i class='material-icons left'>mail</i>".$mail.'</div></a></li>';
       echo '<li onclick=mas()><a ><i class=material-icons>details</i></a></li>';
      }else{
        echo '<li><a href=./loginpage.php> Ingresar</a></li>';     
      }
       ?>      
    </ul>
  </div>
</nav>
</div>
  <nav id='perfilLS' style='display:none'>
    <div class='nav-wrapper orange darken-4'>
      <ul id='nav-mobile' class='right'>
        <?php
          if (isset($_SESSION['user_id'])){
            $sid=$_SESSION['user_id']    ;
            echo '<li><a href=./modifypage.php>'.$MSG_USERINFO.'</a></li>';
            echo '<li><a href=./userinfo.php?user='.$sid.'>'.$sid.'</a></li>';
            $mail=checkmail();
            echo "<li><a href=./mail.php><i class='material-icons left'>mail</i>$mail</a></li>";
            echo '<li><a href=./status.php?user_id='.$sid.'>Reciente</a></li>';
            echo '<li><a href=./logout.php>'.$MSG_LOGOUT.'</a></li>';
          }
          if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])||isset($_SESSION['problem_master_editor'])){
            echo '<li><a href=./admin/>'.$MSG_ADMIN.'</a></li>';
          }
        ?>
      </ul>
    </div>
  </nav>
</div>

<div id="SHORTSCREEN" style="display:none">
<div class='navbar-fixed'>
<nav>
  <div class='nav-wrapper  orange darken-4'>
    <a href='<?php echo $OJ_HOME?>'> 
      <img  height='50'  src='template/og/image/juez-patito2.svg'>    
    </a>
    <ul id="nav-mobile" class="right">
      <li>
        <a href=problemset.php> <i class='material-icons' style="font-size:10px">view_comfy</i> </a>
      </li>
      <li>
        <a href=status.php><i class='material-icons' style="font-size:10px">clear_all</i></a>
      </li>
      <li>
        <a href=ranklist.php><i class='material-icons' style="font-size:10px">equalizer</i> </a>
      </li>
      <li>
        <a href=contest.php><i class=material-icons style="font-size:10px">view_headline</i></a>
      </li>      
      <li>
        <a href=faqs.php><i class='material-icons' style="font-size:10px">question_answer</i></a>
      </li>
      <?php
      if (isset($_SESSION['user_id'])){
        echo "<li><a href=./userinfo.php?user=$sid><i class='material-icons' style='font-size:10px'>account_box</i></a></li>";
	#$mail = checkmail();
        #if ($mail>0) echo "<li><div><a href=./mail.php><i class='material-icons left' style='font-size:10px'>mail</i>$mail</div></a></li>";
       echo "<li onclick=mas()><a><i class=material-icons style='font-size:10px'>details</i></a></li>";
      }else{
        echo "<li><a href=./loginpage.php> <i class='material-icons' style='font-size:10px'>transfer_within_a_station</i></a></li>";     
      }
       ?>      
    </ul>
  </div>
</nav>
</div>
  <nav id='perfilSS' style='display:none'>
    <div class='nav-wrapper orange darken-4'>
      <ul id='nav-mobile' class='right'>
        <?php
          if (isset($_SESSION['user_id'])){
            $sid=$_SESSION['user_id'];
            echo "<li><a href=./modifypage.php><i class='material-icons' style='font-size:10px'>edit</i></a></li>";
            echo "<li><a href=./userinfo.php?user=$sid><i class='material-icons' style='font-size:10px'>person</i></a></li>";
            $mail=checkmail();
            echo "<li><a href=./mail.php style='font-size:10'><i class='material-icons left' style='font-size:10'>mail</i>$mail</a></li>";
            echo "<li><a href=./status.php?user_id=$sid><i class='material-icons left' style='font-size:10'>send</i></a></li>";
            echo "<li><a href=./logout.php><i class='material-icons left' style='font-size:10'>exit_to_app</i></a></li>";
          }
          if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])||isset($_SESSION['problem_master_editor'])){
            echo "<li><a href=./admin/><i class='material-icons left' style='font-size:10'>build</i></a></li>";
          }
        ?>
      </ul>
    </div>
  </nav>
</div>

