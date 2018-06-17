<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
$now=strftime("%Y-%m-%d %H:%M",time());
//if (isset($_GET['cid']))$ucid="&cid=".intval($_GET['cid']);
//else $ucid="";
if (isset($_GET['id'])){//practice
    $id=intval($_GET['id']);    
    $result=mysql_query("SELECT * FROM problem WHERE problem_id=$id");
    if (mysql_num_rows($result)==0){
        $view_errors="<h3>No existe tal problema!..</h3>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    mysql_free_result($result);
    if (isset($_SESSION['administrator'])||
        isset($_SESSION['contest_creator'])||
        isset($_SESSION['problem_master_editor']))
        $sql="SELECT * FROM problem WHERE problem_id=$id";
    else
        $sql="SELECT * FROM problem WHERE problem_id=$id AND defunct='N' AND problem_id NOT IN ( ".
            "SELECT problem_id FROM contest_problem WHERE contest_id IN( ".
            "SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' and `private`='1'))";
    $result=mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result)==0){
        $view_errors="";
        mysql_free_result($result);
        $sql="SELECT contest.contest_id, contest.title, contest_problem.num FROM contest_problem, contest ".
            "WHERE contest.contest_id=contest_problem.contest_id AND contest_problem.problem_id=$id ".
            "AND contest.defunct='N' AND contest.end_time>'$now' AND contest.private='1'";
        $result=mysql_query($sql);
        echo mysql_error(); //OJOOJOJ
        if(mysql_num_rows($result)==1)
            $view_errors.="<h3>Este problema esta siendo usado en el siguiente concurso privado</h3>";
        else
            $view_errors.="<h3>Este problema esta siendo usado en los siguientes concursos privados</h3>";
        while($row=mysql_fetch_object($result)){
            $view_errors.= "<h5><a href=problem.php?cid=$row->contest_id&pid=$row->num>".
                        "Concurso #$row->contest_id:$row->title</a></h5>";
        }
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    $row=mysql_fetch_object($result);
    $view_title= $row->title;    
}else if (isset($_GET['cid']) && isset($_GET['pid'])){//contest
    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);
    if (isset($_SESSION['administrator'])||
        isset($_SESSION['contest_creator'])||
        isset($_SESSION['problem_master_editor']))
        $sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
    else
        $sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<'$now'";
    $result=mysql_query($sql);
    $rows_cnt=mysql_num_rows($result);
    $row=mysql_fetch_row($result);    
    $contest_ok=true;
    if ($row[1] && !isset($_SESSION['c'.$cid])) $contest_ok=false;
    if ($row[2]=='Y') $contest_ok=false;
    if (isset($_SESSION['administrator'])||isset($_SESSION['problem_master_editor']))
        $contest_ok=true;
    if (!$contest_ok){
        $view_errors= "</br></br></br><h3>No estas invitado!...</h3></br></br></br>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }//$co_flag=true;    
    $ok_cnt=$rows_cnt==1;              
    $langmask=$row[0];
    mysql_free_result($result);
    if ($ok_cnt!=1){// not started
        $view_errors="</br></br></br><h3>No hay tal Concurso!...</h3></br></br></br>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }else{// started
        $sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
            SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid)";
    }// public
    $result=mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result)==0){
        $view_errors="</br></br></br><h3>No hay tal problema de tal concurso!...</h3></br></br></br>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    $row=mysql_fetch_object($result);
    $view_title= $row->title;
}else{// ERROR DE URL
    $view_errors="</br></br></br><h3>Error!...</h3></br></br></br>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}

mysql_free_result($result);
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/problem.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
