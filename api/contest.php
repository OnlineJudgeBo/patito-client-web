<?php
require_once('../include/db_info.inc.php');
if(isset($OJ_LANG))
    if(file_exists("../lang/$OJ_LANG.php"))require_once("../lang/$OJ_LANG.php");
require_once('../initPHP.php');
function seconds2str($sec){ //eliminar de temmplate/og/contestrank.php
	return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}
function fillDataContest(&$contest, $id){
    global $PID;
    $view_id=$id;
    $sql="SELECT * FROM `contest` WHERE `contest_id`='$id' ";
    $result=mysql_query($sql);
    $rows_cnt=mysql_num_rows($result);
    $row=mysql_fetch_object($result);       
    $view_title=$row->title;
    $now=time();
    $start_time=strtotime($row->start_time);
    $end_time=strtotime($row->end_time);
    //echo "dat.title=".json_encode($row->title).";";
    $contest->start=$row->start_time;
    $contest->end=$row->end_time;
    $contest->title=$row->title;
    $contest->description=$row->description;
    $contest->private=$row->private;
    $contest->now=date("Y-m-d H:i:s");
    $contest->id=$id;
    //echo "holaaaa:::$row->langmask|||||||||||asfa
    $contest->langmask=$row->langmask;
    //echo "dat.PID=[\"".implode("\",\"", $PID)."\"];
}
function fillContestRank(&$contest, $id){
    global $MSG_CONTEST, $MSG_RANKLIST, $OJ_MEMCACHE, $title, $pid_cnt, $user_cnt, $U, $first_blood,$MSG_RANK, $MSG_USER, $MSG_NICK, $MSG_SOLVED, $MSG_PENALTY, $PID;
    $view_title= $MSG_CONTEST.$MSG_RANKLIST;
    $title="";
    class TM{
        var $solved=0;
        var $time=0;
        var $p_wa_num;
        var $p_ac_sec;
        var $user_id;
        var $nick;
        function TM(){
            $this->solved=0;
            $this->time=0;
            $this->p_wa_num=array(0);
            $this->p_ac_sec=array(0);
        }
        function Add($pid,$sec,$res){
            //              echo "Add $pid $sec $res<br>";
            if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
                return;
            if ($res!=4){
                if(isset($this->p_wa_num[$pid])){
                    $this->p_wa_num[$pid]++;
                }else{
                    $this->p_wa_num[$pid]=1;
                }
            }else{
                $this->p_ac_sec[$pid]=$sec;
                $this->solved++;
                if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
                $this->time+=$sec+$this->p_wa_num[$pid]*1200;
                //                      echo "Time:".$this->time."<br>";
                //                      echo "Solved:".$this->solved."<br>";
            }
        }
    }

    function s_cmp($A,$B){
        //      echo "Cmp....<br>";
        if ($A->solved!=$B->solved) return $A->solved<$B->solved;
        else return $A->time>$B->time;
    }

        // contest start time
        $sql="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$id'";
    //$result=mysql_query($sql) or die(mysql_error());
    //$rows_cnt=mysql_num_rows($result);
    if($OJ_MEMCACHE){
        require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
    }else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
    }

    $start_time=0;
    $end_time=0;
    if ($rows_cnt>0){
        //      $row=mysql_fetch_array($result);

        if($OJ_MEMCACHE)
            $row=$result[0];
        else
            $row=mysql_fetch_array($result);
        $start_time=strtotime($row['start_time']);
        $end_time=strtotime($row['end_time']);
        $title=$row['title'];
        
    }
    if(!$OJ_MEMCACHE)mysql_free_result($result);
    if ($start_time==0){
        $view_errors= "No Such Contest";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }

    if ($start_time>time()){
        $view_errors= "Contest Not Started!";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
    $lock=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;

    //echo $lock.'-'.date("Y-m-d H:i:s",$lock);

    $sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$id'";
    //$result=mysql_query($sql);
    if($OJ_MEMCACHE){
        //        require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
    }else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
    }

    if($OJ_MEMCACHE) $row=$result[0];
    else $row=mysql_fetch_array($result);

    //$row=mysql_fetch_array($result);
    $pid_cnt=intval($row['pbc']);
    if(!$OJ_MEMCACHE)mysql_free_result($result);

    $sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date
                FROM
                        (select * from solution where solution.contest_id='$id' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
    //echo $sql;
    //$result=mysql_query($sql);
    if($OJ_MEMCACHE){
        //     require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
    }else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
    }
    $contest->ranking = new stdClass();
    $contest->ranking->tabla = new stdClass();
    $contest->ranking->tabla->props = new stdClass();
    $contest->ranking->tabla->props->width=(($pid_cnt*125)+500)+"px";
    $contest->ranking->tabla->head = new stdClass();
    $contest->ranking->tabla->head->props = new stdClass();
    $contest->ranking->tabla->head->row = array();
    $contest->ranking->tabla->body = new stdClass();
    $contest->ranking->tabla->body->props = new stdClass();
    $contest->ranking->tabla->body->rows = array();
    //echo "dat.contest.ranking={tabla:{props:{width:\"".(($pid_cnt*125)+500)."px\"}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
    //echo "dat.contest.ranking={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
    //echo "dat.contest.ranking.tabla.head.row.push({text:\"$MSG_RANK\"},{text:\"$MSG_USER\"},{text:\"$MSG_NICK\"},{text:\"$MSG_SOLVED\"},{text:\"$MSG_PENALTY\"});";
    array_push($contest->ranking->tabla->head->row,["text"=>$MSG_RANK], ["text"=>$MSG_USER], ["text"=>$MSG_NICK], ["text"=>$MSG_SOLVED], ["text"=>$MSG_PENALTY]);
    $i=0;
    for ($i=0;$i<$pid_cnt;$i++){
        //echo "dat.contest.ranking.tabla.head.row.push({text:\"$PID[$i]\",click:$i});";
            //."link:\"problem.php?id=$id&pid=$i\"});"; // ya estaba comentado XD
        array_push($contest->ranking->tabla->head->row, ["text"=> $PID[$i],"click"=> $i]);
        //echo "aux.push({});";
        
    }//echo "<td><a href=problem.php?id=$id&pid=$i>$PID[$i]</a></td>";
    $user_cnt=0;
    $user_name='';
    $U=array();
    for ($i=0;$i<$rows_cnt;$i++){       
        if($OJ_MEMCACHE) $row=$result[$i];
        else $row=mysql_fetch_array($result);        
        $n_user=$row['user_id'];        
        if (strcmp($user_name,$n_user)){
            $user_cnt++;
            $U[$user_cnt]=new TM();
            $U[$user_cnt]->user_id=$row['user_id'];
            $U[$user_cnt]->nick=$row['nick'];
            $user_name=$n_user;
        }        
        if(time()<$end_time&&$lock<strtotime($row['in_date']))
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0);
        else
            $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));       
    }
    if(!$OJ_MEMCACHE) mysql_free_result($result);
    usort($U,"s_cmp");

    ////firstblood
    $first_blood=array();
    for($i=0;$i<$pid_cnt;$i++){
        $sql="select user_id from solution where contest_id=$id and result=4 and num=$i order by in_date limit 1";
        $result=mysql_query($sql);
        $row_cnt=mysql_num_rows($result);
        $row=mysql_fetch_array($result);
        if($row_cnt==1){
            $first_blood[$i]=$row['user_id'];
        }else{
            $first_blood[$i]="";
        }

    }
    $rank=1;
    for ($i=0;$i<$user_cnt;$i++){
        array_push($contest->ranking->tabla->body->rows, (object)["props"=>new stdClass(), "row"=>array(new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(), new stdClass())]);
        //echo "dat.contest.ranking.tabla.body.rows.push({props:{},row:[{},{},{},{},{},{}]});\n";
        for ($j=0;$j<$pid_cnt;$j++){
            array_push($contest->ranking->tabla->body->rows[$i]->row, new stdClass());
            //echo "dat.contest.ranking.tabla.body.rows[$i].row.push({});";
        }
        $uuid=$U[$i]->user_id;
        $nick=$U[$i]->nick;
        if($rank==1){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#FFD700\";";
            $contest->ranking->tabla->body->rows[$i]->row[0]->bgColorHTML="#FFD700";
        }
        if($rank==2 || $rank==3){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#C0C0C0\";";
            $contest->ranking->tabla->body->rows[$i]->row[0]->bgColorHTML="#C0C0C0";
        }
        if($rank>=4 && $rank<=6){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#8C7853\";";
            $contest->ranking->tabla->body->rows[$i]->row[0]->bgColorHTML="#8C7853";
        }
        if($nick[0]!="*"){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].text=$rank;"; $rank++;
            $contest->ranking->tabla->body->rows[$i]->row[0]->text=$rank; $rank++;
        }else{
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].text=*;";
            $contest->ranking->tabla->body->rows[$i]->row[0]->text="*";
        }        
        //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].textAlign=\"center\";";
        $contest->ranking->tabla->body->rows[$i]->row[0]->textAlign="center";
        $usolved=$U[$i]->solved;
        if(isset($_GET['user_id']))
            if($uuid==$_GET['user_id']){
                //echo "dat.contest.ranking.tabla.body.rows[$i].row[1].bgcolor=\"red\";";
                $contest->ranking->tabla->body->rows[$i]->row[1]->bgcolor="red";
            }
        $contest->ranking->tabla->body->rows[$i]->row[1]->text=$uuid;
        $contest->ranking->tabla->body->rows[$i]->row[1]->link="userinfo.php?user=$uuid";
        $contest->ranking->tabla->body->rows[$i]->row[2]->text=$U[$i]->nick;
        $contest->ranking->tabla->body->rows[$i]->row[2]->link="userinfo.php?user=$uuid";
        $contest->ranking->tabla->body->rows[$i]->row[3]->text=$usolved;
        $contest->ranking->tabla->body->rows[$i]->row[3]->textAlign="center";
        $contest->ranking->tabla->body->rows[$i]->row[3]->link="status.php?user_id=$uuid&id=$id&jresult=4";
        $contest->ranking->tabla->body->rows[$i]->row[4]->text=sec2str($U[$i]->time);
        $contest->ranking->tabla->body->rows[$i]->row[4]->textAlign="center";
        for ($j=0;$j<$pid_cnt;$j++){
            $bg_color="eeeeee";
            if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
                if($U[$i]->p_wa_num[$j]==0) $bg_color="green lighten-1";
                if($U[$i]->p_wa_num[$j]>0) $bg_color="green lighten-2";
                if($U[$i]->p_wa_num[$j]>3) $bg_color="green lighten-3";
                if($U[$i]->p_wa_num[$j]>7) $bg_color="green lighten-4";
                if($uuid==$first_blood[$j]){                    
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->ctext="white-text";
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->borderBottomColor="#ffeb3b";
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->borderBottomStyle="solid";
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->borderBottomWidth="3px";
                    $bg_color="green accent-4";
                }
            }else if(isset($U[$i]->p_wa_num[$j])) {
                if($U[$i]->p_wa_num[$j]==1) $bg_color="red lighten-5";
                if($U[$i]->p_wa_num[$j]==2) $bg_color="red lighten-4";
                if($U[$i]->p_wa_num[$j]==3) $bg_color="red lighten-3";
                if($U[$i]->p_wa_num[$j]>3) $bg_color="red lighten-2";
                if($U[$i]->p_wa_num[$j]>5) $bg_color="red lighten-1";
            }
            $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->bgcolor=$bg_color;
            $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->textAlign="center";
            if(isset($U[$i])){
                $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->text="";
                if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->text.=seconds2str($U[$i]->p_ac_sec[$j]);
                if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
                    $contest->ranking->tabla->body->rows[$i]->row[($j+5)]->text.="(-".
                                                                                $U[$i]->p_wa_num[$j].")";
            }
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id'])){
        $contest = new stdClass();        
        $id=intval($_GET['id']);
        
        $sql="SELECT * FROM `contest` WHERE `contest_id`='$id' ";
        $result=mysql_query($sql);
        
        if (mysql_num_rows($result)==0){
            mysql_free_result($result);
            //echo "dat.error=\"<h3>No hay tal contest!...</h3>\";";
            echo "No hay tal contest";
            exit();
        }
        
        if(!isset($_SESSION['administrator'])){
            $contest_ok=true; // si esta false es privado 
            $row=mysql_fetch_object($result);
            if ($row->private && !isset($_SESSION['c'.$id]))$contest_ok=false;
            if ($row->defunct=='Y') $contest_ok=false;
            if (time()<strtotime($row->start_time)){
                //echo "dat.error=\"<h3>Recien iniciara el concurso!...</h3>".$row->start_time."\";";
                echo "recien iniciara el contest";
                exit();
            }
            if (!$contest_ok){                
                //echo "dat.contest={onlyContest:1};";
                $contest->onlyContest=1;
                fillDataContest($contest,$id);
                fillContestRank($contest, $id);
                //echo "dat.error=\"<h3>$MSG_PRIVATE_WARNING <a href=contestrank.php?id=$id>".
                //$MSG_WATCH_RANK."</a></h3>\";";
                echo json_encode($contest);
                exit();
            }
        }
        $row=mysql_fetch_object($result);
        $sql="select * from (SELECT problem.problem_id as problem_id, problem.title as title, problem.description as description, problem.input as input, problem.output as output, problem.sample_input as sample_input, problem.sample_output as sample_output, problem.spj as spj, problem.hint as hint, problem.source as source, problem.time_limit as time_limit, problem.memory_limit as memory_limit, problem.submit as submit, problem.accepted as accepted, `contest_problem`.`num` as pnum
 		FROM `contest_problem`,`problem`
 		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` AND `problem`.`defunct`='N'
 		AND `contest_problem`.`contest_id`=$id
 		) problem
 left join (select problem_id pid1,count(1) accepted from solution where result=4 and contest_id=$id group by pid1) p1 on problem.problem_id=p1.pid1
 left join (select problem_id pid2,count(1) submit from solution where contest_id=$id  group by pid2) p2 on problem.problem_id=p2.pid2
 order by pnum
	";
        
        //echo "dat.contest={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}, problem:[], statistics:{tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}}};\n";        
        //echo "dat.contest.tabla.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"$MSG_TITLE\"},{text:\"$MSG_SOURCE\"},{text:\"$MSG_AC\"},{text:\"$MSG_SUBMIT\"});";
        $contest->tabla = new stdClass();
        $contest->tabla->props = new stdClass();
        $contest->tabla->head = new stdClass();
        $contest->tabla->head->props = new stdClass();
        $contest->tabla->head->row = array();
        $contest->tabla->body = new stdClass();
        $contest->tabla->body->props = new stdClass();
        $contest->tabla->body->rows = array();
        
        $contest->statistics = new stdClass();
        $contest->statistics->tabla = new stdClass();
        $contest->statistics->tabla->props = new stdClass();
        $contest->statistics->tabla->head = new stdClass();
        $contest->statistics->tabla->head->props = new stdClass();
        $contest->statistics->tabla->head->row = array();
        $contest->statistics->tabla->body = new stdClass();
        $contest->statistics->tabla->body->props = new stdClass();
        $contest->statistics->tabla->body->rows = array();        
        array_push($contest->tabla->head->row,["text"=>$MSG_PROBLEM_ID], ["text"=>$MSG_TITLE], ["text"=>$MSG_SOURCE], ["text"=>$MSG_AC], ["text"=>$MSG_SUBMITS]);
        $result=mysql_query($sql);// or die(mysql_error());
        //echo $result;
        $view_problemset=Array();
        $i=0;
        while ($row=mysql_fetch_object($result)){
            //echo "dat.contest.tabla.body.rows.push({props:{},row:[{},{},{},{},{}]});\n";
            array_push($contest->tabla->body->rows, (object)["props"=>new stdClass(), "row"=>array(new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass())]);            
            
            $view_problemset[$i][0]="";
            if (isset($_SESSION['user_id'])){
                if(check_ac($id,$i)==1) $contest->tabla->body->rows[$i]->props->bgColor="green";
                if(check_ac($id,$i)==0) $contest->tabla->body->rows[$i]->props->bgColor="red lighten-2";
            }
            $contest->tabla->body->rows[$i]->row[0]->text="Problema $PID[$i] ($row->problem_id)";
            $contest->tabla->body->rows[$i]->row[0]->link="#";
            $contest->tabla->body->rows[$i]->row[0]->click=$i;
            $contest->tabla->body->rows[$i]->row[0]->textAlign="center";
            $contest->tabla->body->rows[$i]->row[1]->text=$row->title;
            $contest->tabla->body->rows[$i]->row[1]->link="#";
            $contest->tabla->body->rows[$i]->row[1]->click=$i;
            $contest->tabla->body->rows[$i]->row[1]->textAlign="center";
            $contest->tabla->body->rows[$i]->row[2]->text=$row->source;
            $contest->tabla->body->rows[$i]->row[2]->link="userinfo.php?user=$row->source";
            $contest->tabla->body->rows[$i]->row[2]->textAlign="center";
            $contest->tabla->body->rows[$i]->row[3]->text=intval($row->accepted);
            $contest->tabla->body->rows[$i]->row[3]->link="status.php?id=$id&problem_id=$PID[$i]&jresult=4";
            $contest->tabla->body->rows[$i]->row[3]->textAlign="center";
            $contest->tabla->body->rows[$i]->row[4]->text=intval($row->submit);
            $contest->tabla->body->rows[$i]->row[4]->link="status.php?id=$id&problem_id=$PID[$i]";
            $contest->tabla->body->rows[$i]->row[4]->textAlign="center";
            //imprimirProb($row, $i, $id, "dat.contest.problem[$i]");
            $i++;
        }        
        mysql_free_result($result);
        
        // DATOS
        fillDataContest($contest,$id);
        fillContestRank($contest, $id);
        
        //////////////////.************************STATISTICS******************

        $sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$id'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $pid_cnt=intval($row[0]);
        mysql_free_result($result);

        $sql="SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`='$id' and num>=0"; 
        $result=mysql_query($sql);
        $R=array();
        while ($row=mysql_fetch_object($result)){
            $res=intval($row->result)-4;
            if ($res<0) $res=8;
            $num=intval($row->num);
            $lag=intval($row->language);
            if(!isset($R[$num][$res]))
                $R[$num][$res]=1;
            else
                $R[$num][$res]++;
            if(!isset($R[$num][$lag+10]))
                $R[$num][$lag+10]=1;
            else
                $R[$num][$lag+10]++;
            if(!isset($R[$pid_cnt][$res]))
                $R[$pid_cnt][$res]=1;
            else
                $R[$pid_cnt][$res]++;
            if(!isset($R[$pid_cnt][$lag+10]))
                $R[$pid_cnt][$lag+10]=1;
            else
                $R[$pid_cnt][$lag+10]++;
            if(!isset($R[$num][8]))
                $R[$num][8]=1;
            else
                $R[$num][8]++;
            if(!isset($R[$pid_cnt][8]))
                $R[$pid_cnt][8]=1;
            else
                $R[$pid_cnt][8]++;
        }
        mysql_free_result($result);

        $res=3600;

        $sql="SELECT (UNIX_TIMESTAMP(end_time)-UNIX_TIMESTAMP(start_time))/100 FROM contest WHERE contest_id=$id ";
        $result=mysql_query($sql);
        $view_userstat=array();
        if($row=mysql_fetch_array($result)){
            $res=$row[0];
        }
        mysql_free_result($result);

        $sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$id'   group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_all= array();
        //echo $sql;
   
        while ($row=mysql_fetch_array($result)){
            $chart_data_all[$row['md']]=$row['c'];
        }
   
        $sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$id' and result=4 group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_ac= array();
   
        while ($row=mysql_fetch_array($result)){
            $chart_data_ac[$row['md']]=$row['c'];
        }
        //echo $contest->statistics->tabla->head->row[0];
        array_push($contest->statistics->tabla->head->row,["text"=>"#"],["text"=>$MSG_AC],["text"=>$MSG_PE],["text"=>$MSG_WA],["text"=>$MSG_TLE],["text"=>$MSG_MLE],["text"=>$MSG_OLE],["text"=>$MSG_RE],["text"=>$MSG_CE],["text"=>"Total"],["text"=>"C"],["text"=>"C++"],["text"=>"Pascal"],["text"=>"Java"],["text"=>"Ruby"],["text"=>"Bash"],["text"=>"Python"],["text"=>"PHP"],["text"=>"Perl"],["text"=>"C#"],["text"=>"Obj-c"],["text"=>"FreeBasic"],["text"=>""]);
        for ($i=0;$i<$pid_cnt;$i++){
            array_push($contest->statistics->tabla->body->rows,(object)["props"=>new stdClass(),"row"=>array(
                new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass(),new stdClass()    )]);
            $contest->statistics->tabla->body->rows[$i]->row[0]=["text"=>$PID[$i], "click"=>$i, "textAlign"=>"center"];
            //echo "<a href='problem.php?id=$id&pid=$i'>$PID[$i]</a>";
            
            for ($j=0;$j<22;$j++) {
                if(isset($R[$i][$j]))
                    $contest->statistics->tabla->body->rows[$i]->row[($j+1)]=["text"=>$R[$i][$j], "textAlign"=>"center"];
                else
                    $contest->statistics->tabla->body->rows[$i]->row[($j+1)]=["text"=>0, "textAlign"=>"center"];
            }
        }
        
        $contest->statistics->graphics=(object)["d1"=>array(), "d2"=>array(), "labels"=>array()];
        foreach($chart_data_all as $k=>$d){
            array_push($contest->statistics->graphics->labels, ($k));
            array_push($contest->statistics->graphics->d1,["x"=>($k), "y"=>$d]);
        }
        
        foreach($chart_data_ac as $k=>$d){		
            //$contest->statistics->graphics->d2.push({x:new Date($k).toLocaleString(), y:$d});";
            array_push($contest->statistics->graphics->d2, ["x"=>($k), "y"=>$d]);
        }
        mysql_free_result($result);        
        //////////////////.************************STATISTICS******************
        echo json_encode($contest);
        exit();
    }else{/////CONTEST SET
        $view_title=$MSG_CONTESTS;
        //echo "dat.title=\"$view_title\";";
        
        $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
        $result=mysql_query($sql);
        $i=0;
        $contestSet = new stdClass();        
        $contestSet->tabla = new stdClass();
        $contestSet->tabla->props = new stdClass();
        $contestSet->tabla->head = new stdClass();
        $contestSet->tabla->head->props = new stdClass();
        $contestSet->tabla->head->row = array();
        $contestSet->tabla->body = new stdClass();
        $contestSet->tabla->body->props = new stdClass();
        $contestSet->tabla->body->rows = array();
        //echo "dat.contestSet={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
        //echo "dat.contestSet.tabla.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"Nombre\"},{text:\"Estado\"},{text:\"Tipo\"});";
        array_push($contestSet->tabla->head->row,["text"=>$MSG_PROBLEM_ID], ["text"=>"Nombre"],["text"=>"Estado"],["text"=>"Tipo"]);
        while ($row=mysql_fetch_object($result)){
            array_push($contestSet->tabla->body->rows, (object)["props"=>new stdClass(), "row"=>array(new stdClass(),new stdClass(),new stdClass(),new stdClass())]);
            //echo "dat.contestSet.tabla.body.rows.push({props:{},row:[{},{},{},{}]});\n";
            $contestSet->tabla->body->rows[$i]->row[0]->text=$row->contest_id;
            $contestSet->tabla->body->rows[$i]->row[0]->link="#";
            $contestSet->tabla->body->rows[$i]->row[0]->click=$row->contest_id;
            $contestSet->tabla->body->rows[$i]->row[0]->textAlign="center";
            $contestSet->tabla->body->rows[$i]->row[1]->text=$row->title;
            $contestSet->tabla->body->rows[$i]->row[1]->link="#";
            $contestSet->tabla->body->rows[$i]->row[1]->click=$row->contest_id;
            $start_time=strtotime($row->start_time);
            $end_time=strtotime($row->end_time);
            $now=time();
            $length=$end_time-$start_time;
            $left=$end_time-$now;
            if ($now>$end_time) {// past
                //echo "dat.contestSet.tabla.body.rows[$i].row[2].text=\"$MSG_Ended@$row->end_time\";";
                $contestSet->tabla->body->rows[$i]->row[2]->text="$MSG_Ended@$row->end_time";
            }else if ($now<$start_time){// pending
                $contestSet->tabla->body->rows[$i]->row[2]->text="$MSG_Start@$row->start_time$MSG_TotalTime"
                                                                .formatTimeLength($length);
                $contestSet->tabla->body->rows[$i]->row[2]->ctext="green-text";
            }else{// running
                $contestSet->tabla->body->rows[$i]->row[2]->text="$MSG_Running $MSG_LeftTime "
                                                                .formatTimeLength($left);
                $contestSet->tabla->body->rows[$i]->row[2]->ctext="red-text";
            }
            $contestSet->tabla->body->rows[$i]->row[2]->textAlign="center";
            $private=intval($row->private);
            if ($private==0){
                $contestSet->tabla->body->rows[$i]->row[3]->text=$MSG_Public;
                $contestSet->tabla->body->rows[$i]->row[3]->ctext="green-text";
            }else{
                $contestSet->tabla->body->rows[$i]->row[3]->text=$MSG_Private;
                $contestSet->tabla->body->rows[$i]->row[3]->ctext="red-text";
            }
            $contestSet->tabla->body->rows[$i]->row[3]->textAlign="center";
            $i++;
        }        
        mysql_free_result($result);
        echo json_encode($contestSet); exit();
    }
    header("HTTP/1.1 400 Bad Request");
}

?>
