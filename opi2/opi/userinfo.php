<?php
$cache_time=10; 
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once("./initPHP.php");
// check user
$user=$_GET['user'];
if (!is_valid_user_name($user)){
    $view_errors="</br></br></br><h3>Usuario no valido!...</h3></br></br></br>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
$view_title=$user;
$user_mysql=mysql_real_escape_string($user);
$sql="SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`='$user_mysql'";
$result=mysql_query($sql);
$row_cnt=mysql_num_rows($result);
if ($row_cnt==0){
    $view_errors="</br></br></br><h3>No hay tal usuario!...</h3></br></br></br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
$row=mysql_fetch_object($result);
$school=$row->school;
$email=$row->email;
$nick=$row->nick;
mysql_free_result($result);
// count solved
$sql="SELECT count(DISTINCT problem_id) as `ac` FROM `solution` WHERE `user_id`='".$user_mysql."' AND `result`=4";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$AC=$row->ac;
mysql_free_result($result);
// count submission
$sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$Submit=$row->Submit;
mysql_free_result($result);
// update solved 
$sql="UPDATE `users` SET `solved`='".strval($AC)."',`submit`='".strval($Submit)."' WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql);
$sql="SELECT count(*) as `Rank` FROM `users` WHERE `solved`>$AC";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$Rank=intval($row[0])+1;

$i=0;
$sql="SELECT result,count(1) FROM solution WHERE `user_id`='$user_mysql'  AND result>=4 group by result order by result";
$result=mysql_query($sql);
$view_userstat=array();
while($row=mysql_fetch_array($result)){
    $view_userstat[$i++]=$row;
}
mysql_free_result($result);

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql'   group by md order by md desc ";
$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_all= array();
//echo $sql;
    
while ($row=mysql_fetch_array($result)){
    $chart_data_all[$row['md']]=$row['c'];
}
    
$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and result=4 group by md order by md desc ";
$result=mysql_query($sql);//mysql_escape_string($sql));
$chart_data_ac= array();
//echo $sql;
    
while ($row=mysql_fetch_array($result)){
    $chart_data_ac[$row['md']]=$row['c'];
}
  
mysql_free_result($result);
    
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/userinfo.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

