<?php
require_once('../include/db_info.inc.php');
if(isset($OJ_LANG))
    if(file_exists("../lang/$OJ_LANG.php"))require_once("../lang/$OJ_LANG.php");
function firstProblem(){return 1000;}
function numProblemPag(){return 100;}
function numPagProblemSet(){
    $result=mysql_query("SELECT max(`problem_id`) as upid FROM `problem`");
    echo mysql_error();
    $row=mysql_fetch_object($result);
    return ceil((intval($row->upid)-firstProblem())/numProblemPag());
}
function getPage(){
    $page=1;
    if (isset($_GET['page'])) $page=intval($_GET['page']);
    return $page;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $now=strftime("%Y-%m-%d %H:%M",time());
    if (isset($_GET['id'])){//practice
        $id=intval($_GET['id']);
        $result=mysql_query("SELECT * FROM problem WHERE problem_id=$id");
        if (mysql_num_rows($result)==0){
            //echo "dat.error=\"<h3>No existe tal problema!..</h3>\";";
            echo "No existe tal problema!";
            exit();
        }
        mysql_free_result($result);
        if (isset($_SESSION['administrator'])||
            isset($_SESSION['contest_creator'])||
            isset($_SESSION['problem_master_editor']))
            $sql="SELECT * FROM problem WHERE problem_id=$id";
        else
            $sql="SELECT * FROM problem WHERE problem_id=$id AND defunct='N' AND problem_id NOT IN ( ".
                "SELECT problem_id FROM contest_problem WHERE contest_id IN( ".
                "SELECT contest_id FROM contest WHERE end_time>'$now' and private='1'))";
        $result=mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($result)==0){ // corregir
            echo "dat.error=\"\";";
            mysql_free_result($result);
            $sql="SELECT contest.contest_id, contest.title, contest_problem.num FROM contest_problem, contest ".
                "WHERE contest.contest_id=contest_problem.contest_id AND contest_problem.problem_id=$id ".
                "AND contest.defunct='N' AND contest.end_time>'$now' AND contest.private='1'";
            $result=mysql_query($sql); echo mysql_error(); //OJOOJOJ
            if(mysql_num_rows($result)==1){
                echo "dat.error+=\"<h3>Este problema esta siendo usado en el siguiente concurso privado</h3>\";";
            }else{
                echo "dat.error+=\"<h3>Este problema esta siendo usado en los siguientes concursos privados</h3>\";";
            }
            while($row=mysql_fetch_object($result)){
                echo "dat.error+=\"<h5><a href=contest.php?cid=$row->contest_id>\";";
                echo "dat.error+=\"Concurso #$row->contest_id:$row->title problema ".$PID[$row->num]."</a></h5>\";";
            }
            exit();
        }
        $row=mysql_fetch_object($result);
        $view_title= $row->title;
        mysql_free_result($result);
        $problem = new stdClass();
        $problem->id=$row->problem_id; // number
        $problem->title=$row->title; //string
        //if($pid==-1)
        $problem->showTitle=json_encode($row->title); //string
        /*else{
            $problem->showTitle="$MSG_PROBLEM $PID[$pid]: $row->title";
            $problem->pId=$pid;
            $problem->cId=$cid;
            }*/
        $problem->time=$row->time_limit; // number
        $problem->mem=$row->memory_limit; // number
        $problem->submit=$row->submit; // number
        $problem->ac=$row->accepted; // number
        $problem->spj=$row->spj; // bool
        $problem->des=$row->description; // string
        $problem->input=$row->input; // string
        $problem->output=$row->output; // string
        $problem->sinput=$row->sample_input; // string
        $problem->soutput=$row->sample_output; // string
        $problem->hint=$row->hint; //string
        $problem->source=$row->source; // string
        header("HTTP/1.1 200 OK");
        echo json_encode($problem);
        exit();
    }
    $problemSet = new stdClass();
    $problemSet->pages = new stdClass();
    $problemSet->tabla = new stdClass();
    $problemSet->tabla->props = new stdClass();
    $problemSet->tabla->head = new stdClass();
    $problemSet->tabla->head->props = new stdClass();
    $problemSet->tabla->head->row = array();
    $problemSet->tabla->body = new stdClass();
    $problemSet->tabla->body->props = new stdClass();
    $problemSet->tabla->body->rows = array();
    $view_title=$MSG_PROBLEMS;
    $subArr=Array();
    if (isset($_SESSION['user_id'])){
        $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
            " group by `problem_id`";
        $result=@mysql_query($sql) or die(mysql_error());
        while ($row=mysql_fetch_array($result))
            $subArr[$row[0]]=false;		
    }
    if (isset($_SESSION['user_id'])){
        $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
            " AND `result`=4".
            " group by `problem_id`";
        $result=@mysql_query($sql) or die(mysql_error());
        while ($row=mysql_fetch_array($result))
            $subArr[$row[0]]=true;		
    }
    $pstart=firstProblem()+numProblemPag()*intval(getPage())-numProblemPag();
    $pend=$pstart+numProblemPag();
    if(isset($_GET['search'])&&trim($_GET['search'])!=""){
        $search=mysql_real_escape_string($_GET['search']);
        $filter_sql=" ( title like '%$search%' or source like '%$search%')";
        $problemSet->pages->total=0;
    }else{
        $filter_sql="`problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
        $problemSet->pages->total=numPagProblemSet();
        $problemSet->pages->in=getPage();
    }
    if (isset($_SESSION['administrator'])){	// HJHJHJ
        $sql="SELECT * FROM `problem` WHERE $filter_sql ";	
    }else{
        $now=strftime("%Y-%m-%d %H:%M",time());
        $sql="SELECT * FROM `problem` ".
            "WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN(
		SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now' or private=1)and `defunct`='N') ) ";
    }
    $sql.=" ORDER BY `problem_id`";    
    $result=mysql_query($sql) or die(mysql_error());
    array_push($problemSet->tabla->head->row,["text"=>$MSG_PROBLEM_ID], ["text"=>$MSG_TITLE], ["text"=>$MSG_SOURCE], ["text"=>$MSG_AC], ["text"=>$MSG_SUBMITS]);    
    $i=0;
    while ($row=mysql_fetch_object($result)){
        array_push($problemSet->tabla->body->rows, (object)["props"=>new stdClass(), "row"=>array(new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass())]);
        $problemSet->tabla->body->rows[$i]->props->bgColor="";
        if (isset($subArr[$row->problem_id])){
            if ($subArr[$row->problem_id]){
                $problemSet->tabla->body->rows[$i]->props->bgColor="green lighten-2";
            }else{
                $problemSet->tabla->body->rows[$i]->props->bgColor="red lighten-2";
            }
        }        
        $problemSet->tabla->body->rows[$i]->row[0]->text=$row->problem_id;
        $problemSet->tabla->body->rows[$i]->row[0]->link="#";
        $problemSet->tabla->body->rows[$i]->row[0]->click=$i;
        $problemSet->tabla->body->rows[$i]->row[0]->textAlign="center";
        $problemSet->tabla->body->rows[$i]->row[1]->text=$row->title;
        $problemSet->tabla->body->rows[$i]->row[1]->link="#";
        $problemSet->tabla->body->rows[$i]->row[1]->click=$i;
        $problemSet->tabla->body->rows[$i]->row[2]->text=$row->source;
        $problemSet->tabla->body->rows[$i]->row[2]->link="userinfo.php?user=$row->source";
        $problemSet->tabla->body->rows[$i]->row[2]->textAlign="center";
        $problemSet->tabla->body->rows[$i]->row[3]->text=$row->accepted;
        $problemSet->tabla->body->rows[$i]->row[3]->link="status.php?id=$row->problem_id&jresult=4";
        $problemSet->tabla->body->rows[$i]->row[3]->textAlign="center";
        $problemSet->tabla->body->rows[$i]->row[4]->text=$row->submit;
        $problemSet->tabla->body->rows[$i]->row[4]->link="status.php?id=$row->problem_id";
        $problemSet->tabla->body->rows[$i]->row[4]->textAlign="center";
        $i++;
    }
    header("HTTP/1.1 200 OK");
    echo json_encode($problemSet);
    exit();
    mysql_free_result($result);
    header("HTTP/1.1 400 Bad Request");
}

?>
