<?php
require_once("include/db_info.inc.php");
$languageName=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Other Language");
$jresult=Array($MSG_PD,$MSG_PR,$MSG_CI,$MSG_RJ,$MSG_AC,$MSG_PE,$MSG_WA,$MSG_TLE,$MSG_MLE,$MSG_OLE,$MSG_RE,$MSG_CE,$MSG_CO,$MSG_TR);
$PIDS=Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");
$judge_result=Array($MSG_Pending,$MSG_Pending_Rejudging,$MSG_Compiling,$MSG_Running_Judging,$MSG_Accepted,$MSG_Presentation_Error,$MSG_Wrong_Answer,$MSG_Time_Limit_Exceed,$MSG_Memory_Limit_Exceed,$MSG_Output_Limit_Exceed,$MSG_Runtime_Error,$MSG_Compile_Error,$MSG_Compile_OK,$MSG_TEST_RUN);
$judge_color=Array("gray","gray","orange","orange","green","red","red","red","red","red","red","navy ","navy");
$language_name=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Other Language");
$language_ext=Array( "c", "cc", "pas", "java", "rb", "sh", "py", "php","pl", "cs","m","bas" );
//$language_ext=Array( "c", "cpp", "pas", "java", "rb", "sh", "python", "php","pl", "cs","m","bas" );
//$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; OJO
function indexIniFunBas(){   
    $view_news="";
    $sql=	"SELECT * "
        ."FROM `news` "
        ."WHERE `defunct`!='Y'"
        ."ORDER BY `importance` ASC,`time` DESC "
        ."LIMIT 5";
    $result=mysql_query($sql);//mysql_escape_string($sql));

    if (!$result){
        $view_news= "<h3>No hay noticias</h3>";
        $view_news.= mysql_error();
    }else{
        $view_news.= "";
        while ($row=mysql_fetch_object($result)){
            $view_news.= "<b>".$row->title."</b>";
            $view_news.= "<h2>[".$row->user_id."]</h2>";
            $view_news.= $row->content;
        }
        mysql_free_result($result);
    }
    //////////////
    //////////////// Ultimos blog 
    $view_blog="";
    $sql=	"SELECT * "
        ."FROM `blog` "
        ."ORDER BY `date` DESC,`date` ASC "
        ."LIMIT 8";
    $result=mysql_query($sql);//mysql_escape_string($sql));
    if (!$result){
        $view_blog= "<h3>El blog esta vacio :( </h3>";
        $view_blog.= mysql_error();
    }else{
        $view_news.= "";
        while ($row=mysql_fetch_object($result)){
            $view_blog.="<div id='blog_node'>";
            $view_blog.= "<div id='title'><a href=blog.php?blog=$row->blog_id>".$row->title."</a></div>";
            $view_blog.= "<div id='by'>Por [".$row->user_id."]</div>";
            if($_SESSION['user_id'] == $row->user_id){
                $view_blog.= "<b> <a href=blog_edit.php?blog=$row->blog_id > Editar </a></b>";
            }
            $view_blog.="<div id='content'>". $row->content."</div>";
            //cargar los comentarios segun el blog construccion
            //$view_blog.= "<p>"$row->comets;
            $view_blog.='</div>';
        }
        //mysql_free_result($result);
    }


    //
    $view_apc_info="";

    $sql="SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc ";
    $result=mysql_query($sql);//mysql_escape_string($sql));
    $chart_data_all= array();
    //echo $sql;

    while ($row=mysql_fetch_array($result)){
        $chart_data_all[$row['md']]=$row['c'];
    }

    $sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc ";
    $result=mysql_query($sql);//mysql_escape_string($sql));
    $chart_data_ac= array();
    //echo $sql;

    while ($row=mysql_fetch_array($result)){
        $chart_data_ac[$row['md']]=$row['c'];
    }
}
function checkmail(){
    if(!isset($_SESSION['user_id'])) return "";
    $sql="SELECT count(1) FROM `mail` WHERE new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
    $result=mysql_query($sql);
    if(!$result) return 0;
    $row=mysql_fetch_row($result);
    $retmsg=$row[0];
    mysql_free_result($result);
    return $retmsg;
}

function checkcontest($MSG_CONTEST){
    require_once("./include/db_info.inc.php");
    $now=strftime("%Y-%m-%d %H:%M",time());
    $sql="SELECT count(*) FROM `contest` WHERE `end_time`>'$now' AND `defunct`='N'";
    $result=mysql_query($sql);
    $row=mysql_fetch_row($result);
    if (intval($row[0])==0) $retmsg=$MSG_CONTEST;
    else $retmsg=$row[0]." $MSG_CONTEST";
    mysql_free_result($result);
    return $retmsg;
}
function crearlistContest(){
    echo "var listContest=[];";
    $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
    $result=mysql_query($sql);
    $view_contest=Array();
    while ($row=mysql_fetch_object($result)){
        $fecha=date("Y-m-d H:i:s");   
        if (intval($row->private)!=0) continue;
        if (time()>strtotime($row->end_time)) continue;
        echo "listContest.push({id:$row->contest_id, title:\"$row->title\", start:\"$row->start_time\", end:\"$row->end_time\", now:\"$fecha\"});";
    }
    mysql_free_result($result);
}
function firstPro(){return 1000;}
function numProbPag(){return 100;}
function numPagProbset(){
    $result=mysql_query("SELECT max(`problem_id`) as upid FROM `problem`");
    echo mysql_error();
    $row=mysql_fetch_object($result);
    return ceil((intval($row->upid)-firstPro())/numProbPag());
}
function page(){
    $page="1";
    if (isset($_GET['page'])) $page=intval($_GET['page']);
    return $page;
}
function arrSub(){
    $subArr=Array();
    // sub_arry vector donde [problem_id] esta en true si le acepto y false si no 
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
    return $subArr;
}
function crearTablaProblemSet(){
    global $MSG_PROBLEM_ID,$MSG_TITLE,$MSG_SOURCE,$MSG_AC;
    // sub_arry vector donde [problem_id] esta en true si le acepto y false si no 
    $subArr=arrSub();
    $pstart=firstPro()+numProbPag()*intval(page())-numProbPag();
    $pend=$pstart+numProbPag();

    if(isset($_GET['search'])&&trim($_GET['search'])!=""){
        $search=mysql_real_escape_string($_GET['search']);
        $filter_sql=" ( title like '%$search%' or source like '%$search%')";	
    }else
        $filter_sql="`problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";

    if (isset($_SESSION['administrator'])){	
        $sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted`,`tags` FROM `problem` WHERE $filter_sql ";	
    }else{
        $now=strftime("%Y-%m-%d %H:%M",time());
        $sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted`,`tags` FROM `problem` ".
            "WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN(
		SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now' or private=1)and `defunct`='N')
) ";
    }
    $sql.=" ORDER BY `problem_id`";
    
    $result=mysql_query($sql) or die(mysql_error());
    echo "var TablaPS={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";    
    echo "TablaPS.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"$MSG_TITLE\"},{text:\"$MSG_SOURCE\"},{text:\"$MSG_AC\"},{text:\"Envios\"});";
    $i=0;
    while ($row=mysql_fetch_object($result)){
        echo "TablaPS.body.rows.push({props:{},row:[{},{},{},{},{}]});\n";
        echo "TablaPS.body.rows[$i].props.bgColor=\"\";\n";
        if (isset($subArr[$row->problem_id])){
            if ($subArr[$row->problem_id]){
                echo "TablaPS.body.rows[$i].props.bgColor=\"green lighten-2\";";
            }else{
                echo "TablaPS.body.rows[$i].props.bgColor=\"red lighten-2\";";
            }
        }
        echo "TablaPS.body.rows[$i].row[0].text=$row->problem_id;";
        echo "TablaPS.body.rows[$i].row[0].link=\"problem.php?id=$row->problem_id\";";
        echo "TablaPS.body.rows[$i].row[1].text=\"$row->title\";";
        echo "TablaPS.body.rows[$i].row[1].link=\"problem.php?id=$row->problem_id\";";
        echo "TablaPS.body.rows[$i].row[2].text=\"$row->source\";";
        echo "TablaPS.body.rows[$i].row[2].link=\"userinfo.php?user=$row->source\";";
        echo "TablaPS.body.rows[$i].row[3].text=$row->accepted;";
        echo "TablaPS.body.rows[$i].row[3].link=\"status.php?id=$row->problem_id&jresult=4\";";
        echo "TablaPS.body.rows[$i].row[4].text=$row->submit;";
        echo "TablaPS.body.rows[$i].row[4].link=\"status.php?id=$row->problem_id\";";
        $i++;
    }
    mysql_free_result($result);
}
$top;
$bottom;
function crearListStatusTabla(){
    global $MSG_Manual, $MSG_AC, $MSG_WA, $jresult, $PIDS, $judge_result, $judge_color,
        $language_name, $MSG_Explain, $MSG_OK, $top, $bottom, $OJ_SIM, $OJ_MEMCACHE,
        $OJ_SHOW_DIFF, $MSG_RUNID, $MSG_USER, $MSG_PROBLEM, $MSG_RESULT, $MSG_MEMORY,
        $MSG_TIME, $MSG_LANG, $MSG_CODE_LENGTH, $MSG_SUBMIT_TIME;/// TOP Corregir
    
    $lock      = false;
    $lock_time = date("Y-m-d H:i:s", time());
    $sql       = "SELECT * FROM `solution` WHERE problem_id>0 ";
    if (isset($_GET['cid'])) {
        $cid        = intval($_GET['cid']);
        $sql        = $sql." AND `contest_id`='$cid' and num>=0 ";
        $sql_lock   = "SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
        $result     = mysql_query($sql_lock) or die(mysql_error());
        $rows_cnt   = mysql_num_rows($result);
        $start_time = 0;
        $end_time   = 0;
        if ($rows_cnt > 0) {
            $row        = mysql_fetch_array($result);
            $start_time = strtotime($row[0]);
            $title      = $row[1];
            $end_time   = strtotime($row[2]);
        }
        $lock_time = $end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
        //$lock_time=date("Y-m-d H:i:s",$lock_time);
        $time_sql = "";
        //echo $lock.'-'.date("Y-m-d H:i:s",$lock);
        if (time() > $lock_time && time() < $end_time) {
            //$lock_time=date("Y-m-d H:i:s",$lock_time);
            //echo $time_sql;
            $lock = true;
        } else {
            $lock = false;
        }

        //require_once("contest-header.php");
    } else {
        //require_once("oj-header.php");
        if (isset($_SESSION['administrator']) || isset($_SESSION['source_browser']) || (isset($_SESSION['user_id']) && $_GET['user_id'] == $_SESSION['user_id'])) {
            if ($_SESSION['user_id'] != "guest") {
                //	$sql = "SELECT * FROM `solution` WHERE contest_id is null "; //cambio ultimo pedido lic_teran
            }
        } else {
            $sql = "SELECT * FROM `solution` WHERE problem_id>0 and contest_id is not null ";
        }
    }
    $start_first = true;
    $order_str   = " ORDER BY `solution_id` DESC ";

    // check the top arg
    if (isset($_GET['top'])) {
        $top                  = strval(intval($_GET['top']));
        if ($top != -1) {$sql = $sql."AND `solution_id`<='".$top."' ";
        }
    }
    // check the problem arg OG ////////OJOJOJOJ


    $problem_id=getProblemId();
    if ($problem_id!="") {
        if (isset($_GET['cid'])) {
            $num=array_search(strval($problem_id), $PIDS);;
            $sql=$sql."AND `num`='".$num."' ";
        } else {
            $sql.="AND `problem_id`='".$problem_id."' "; //aquise usa $problem_id;
        }
    }


    // check the user_id arg OG/////////OJOJOJOJOO
    $user_id=getUserId();
    if ($user_id != "") $sql.="AND `user_id`='".$user_id."' ";

    $language=getLanguage();
    if ($language != -1) $sql.="AND `language`='".strval($language)."' ";

    $result=getJresult($jresult);
    if ($result != -1 && !$lock) $sql.="AND `result`='".strval($result)."' ";


    if ($OJ_SIM) {
        $old = $sql;
        $sql = "select * from ($sql order by solution_id desc limit 1000) solution left join `sim` on solution.solution_id=sim.s_id WHERE 1 ";
        if (isset($_GET['showsim']) && intval($_GET['showsim']) > 0) {
            $showsim = intval($_GET['showsim']);
            $sql     = "select * from ($old ) solution
                     left join `sim` on solution.solution_id=sim.s_id WHERE result=4 and sim>=$showsim limit 1000";
            $sql = "SELECT * FROM ($sql) `solution`
                        left join(select solution_id old_s_id,user_id old_user_id from solution limit 1000) old
                        on old.old_s_id=sim_s_id WHERE  old_user_id!=user_id and sim_s_id!=solution_id ";
        }
        //$sql=$sql.$order_str." LIMIT 20";
    }

    $sql = $sql.$order_str." LIMIT 23";    
    ///termina de armarse $sql********************************************************8
    //echo $sql;

    if ($OJ_MEMCACHE) {
        require ("./include/memcache.php");
        $result = mysql_query_cache($sql);
        // or die("Error! ".mysql_error());
        if ($result) {$rows_cnt = count($result);
        } else {
            $rows_cnt = 0;
        }
    } else {

        $result = mysql_query($sql);
        // or die("Error! ".mysql_error());

        if ($result) {$rows_cnt = mysql_num_rows($result);
        } else {
            $rows_cnt = 0;
        }
    }

    $top = $bottom = -1;
    $cnt = 0;
    if ($start_first) {
        $row_start = 0;
        $row_add   = 1;
    } else {
        $row_start = $rows_cnt-1;
        $row_add   = -1;
    }

    $view_status = Array();
    //echo "var listStatus=[];";
    echo "var TablaS={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    echo "TablaS.head.row.push({text:\"$MSG_RUNID\"},{text:\"$MSG_USER\"},{text:\"$MSG_PROBLEM\"},{text:\"$MSG_RESULT\"},{text:\"$MSG_MEMORY\"},{text:\"$MSG_TIME\"},{text:\"$MSG_LANG\"},{text:\"$MSG_CODE_LENGTH\"},{text:\"$MSG_SUBMIT_TIME\"});";
    if (isset($_SESSION['http_judge'])) {
            echo "TablaS.head.row.push({text:\"Juzgar Manual\"});";
    }
    $last = 0;
    for ($i = 0; $i < $rows_cnt; $i++) {
        if ($OJ_MEMCACHE) {
            $row = $result[$i];
        } else {
            $row = mysql_fetch_array($result);
        }
        if ($i == 0 && $row['result'] < 4) {
            $last = $row['solution_id'];
        }

        if ($top == -1) {
            $top = $row['solution_id'];
        }

        $bottom = $row['solution_id'];
        $flag   = (is_running(intval($row['contest_id']))) ||
                isset($_SESSION['source_browser']) ||
                isset($_SESSION['administrator']) ||
                (isset($_SESSION['user_id']) && !strcmp($row['user_id'], $_SESSION['user_id']));

        $cnt = 1-$cnt;
        //echo "listStatus.push(new Array({},{},{},{},{},{},{},{},{}));";
        echo "TablaS.body.rows.push({props:{},row:[{},{},{},{},{},{},{},{},{}]});\n";
        
        echo "TablaS.body.rows[$i].row[0].text=".$row['solution_id'].";";
        if ( (isset($_SESSION['user_id']) && strtolower($row['user_id']) == strtolower($_SESSION['user_id'])) || isset($_SESSION['source_browser']) ) {
            echo "TablaS.body.rows[$i].row[0].link=\"showsource.php?id=".$row['solution_id']."\";";
        }            
        echo "TablaS.body.rows[$i].row[1].text=\"".$row['user_id']."\";";
        if ($row['contest_id'] > 0) {
            echo "TablaS.body.rows[$i].row[1].link=\"contestrank.php?cid=".$row['contest_id']."&user_id=".$row['user_id']."#".$row['user_id']."\";";            
        } else {
            echo "TablaS.body.rows[$i].row[1].link=\"userinfo.php?user=".$row['user_id']."\";";
        }
        
        if ($row['contest_id'] > 0) {
            echo "TablaS.body.rows[$i].row[2].link=\"problem.php?cid=".$row['contest_id']."&pid=".$row['num']."\";";
            if (isset($cid)) {
                echo "TablaS.body.rows[$i].row[2].text=\"".$PIDS[$row['num']]."\";";
            } else {
                echo "TablaS.body.rows[$i].row[2].text=\"".$row['problem_id']."\";";
            }
        } else {
            echo "TablaS.body.rows[$i].row[2].link=\"problem.php?id=".$row['problem_id']."\";";
            echo "TablaS.body.rows[$i].row[2].text=\"".$row['problem_id']."\";";
        }
        echo "TablaS.body.rows[$i].row[3].text=\"".$judge_result[$row['result']]."\";\n";
       
        if (intval($row['result']) == 11 && ((isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) || isset($_SESSION['source_browser']))) {
            echo "TablaS.body.rows[$i].row[3].link=\"ceinfo.php?sid=".$row['solution_id']."\";";
        } else
            if (((intval($row['result']) == 6 && $OJ_SHOW_DIFF) || $row['result'] == 10 || $row['result'] == 13) && ((isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) || isset($_SESSION['source_browser']))) {
                echo "TablaS.body.rows[$i].row[3].link=\"reinfo.php?sid=".$row['solution_id']."\";";
            } else {
                if (!$lock || $lock_time > $row['in_date'] || $row['user_id'] == $_SESSION['user_id']) {
                    if ($OJ_SIM && $row['sim'] > 80 && $row['sim_s_id'] != $row['s_id']) {
                        echo "TablaS.body.rows[$i].row[3].text+=\"*[".$row['sim_s_id']."](".$row['sim']."%)\";";
                        if (isset($_SESSION['source_browser'])) {                           
                            echo "TablaS.body.rows[$i].row[3].link=\"comparesource.php?left=".$row['sim_s_id']."&right=".$row['solution_id']."\";";
                        }
                        if (isset($_GET['showsim']) && isset($row[13])) {
                            //listStatus[$i][3] .= "$row[13]"; la fecha de la copia
                        }
                    }
                }
            }
        if ($row['result'] != 4 && isset($row['pass_rate']) && $row['pass_rate'] > 0 && $row['pass_rate'] < .98) { //ni idea que sea esto
            //echo "listStatus[$i][3].= "<span class='btn btn-info'>".(100-$row['pass_rate']*100)."%%%</span>";
        }
        $form="";
        if (isset($_SESSION['http_judge'])) {
            echo "TablaS.body.rows[$i].row.push({});";
            echo "TablaS.body.rows[$i].row[9].text=\"<form method=post action='admin/problem_judge.php'><input type='hidden' name='sid' value='".$row['solution_id']."'>\";";            
            echo "TablaS.body.rows[$i].row[9].text+=\"<select class='btn input-small' length=4 name=result>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='0'>$MSG_Manual</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='4'>$MSG_AC</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='6'>$MSG_WA</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"</select>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<input class='btn input-small' title='$MSG_Explain' type='text' name='explain'>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<button class='btn waves-effect'  type='submit' name='manual'>$MSG_OK</button>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"</form>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<Header dat={dat} msg={msg}/>\";";
        }

        if ($row['result'] >= 4) { // 1 , 2, 3, ni idea 
            echo "TablaS.body.rows[$i].row[4].text=\"".$row['memory']."\";";
            echo "TablaS.body.rows[$i].row[5].text=\"".$row['time']."\";";
        } else {
            echo "TablaS.body.rows[$i].row[4].text=\"---\";";
            echo "TablaS.body.rows[$i].row[5].text=\"---\";";
        }
        echo "TablaS.body.rows[$i].row[6].text=\"".$language_name[$row['language']]."\";";
        if ((isset($_SESSION['user_id']) && strtolower($row['user_id']) == strtolower($_SESSION['user_id']) || isset($_SESSION['source_browser']) )) {            
            if (isset($cid)) {
                echo "TablaS.body.rows[$i].row[6].link=\"submitpage.php?cid=".$cid."&pid=".$row['num']."&sid=".$row['solution_id']."\";";
            } else {
                echo "TablaS.body.rows[$i].row[6].link=\"submitpage.php?id=".$row['problem_id']."&sid=".$row['solution_id']."\";";
            }
        }
        echo "TablaS.body.rows[$i].row[7].text=\"".$row['code_length']." B\";";
        echo "TablaS.body.rows[$i].row[8].text=\"".$row['in_date']."\";\n";

    }
    if (!$OJ_MEMCACHE) {mysql_free_result($result);
    }

}

function getProblemId(){ // check the problem arg
    $problem_id = "";
    if (isset($_GET['problem_id'])) $problem_id=intval($_GET['problem_id']);
    else return "";
    if( !(isset($_GET['cid'])) && (strval(intval($_GET['problem_id']))== '0') )
        $problem_id="";
    return $problem_id;
}

function getUserId(){
    $user_id = "";
    if (isset($_GET['user_id'])) {
        $user_id = trim($_GET['user_id']);
        if ( !(is_valid_user_name($user_id)) ){
            $user_id = "";
        }
    }
    return $user_id;
}
function getLanguage(){
    global $languageName;
    $lan=-1;
    if (isset($_GET['language'])) $lan=intval($_GET['language']);
    if($lan<0||$lan>=count($languageName)) $lan=-1;
    return $lan;
}

function getJresult($jRes){
    $jres=-1;
    if (isset($_GET['jresult'])) $jres=intval($_GET['jresult']);
    if($jres<0||$jres>=count($jRes)) $jres=-1;
    return $jres;
}
function getShowsim(){
    global $OJ_SIM;
    $ss=0;
    if($OJ_SIM) if (isset($_GET['showsim'])) $ss=$_GET['showsim'];
    return $ss;
}
function getId(){
    $ans="";
    if (isset($_GET['id'])) $ans=$_GET['id'];
    return $ans;
}
function getCid(){
    $ans="";
    if (isset($_GET['cid'])) $ans=$_GET['cid'];
    return $ans;
}
function getLangmask(){
    global $OJ_LANGMASK;
    $ans=$OJ_LANGMASK;
    if(isset($_GET['langmask']))
        $ans=$_GET['langmask'];
    return $ans;
}
function getPid(){
    $ans="";    
    if (isset($_GET['pid']))
        $ans=intval($_GET['pid']);
    return $ans;
}
function cookieLastlang(){
    $ans=0;
    if(isset($_COOKIE['lastlang']))
        $ans=$_COOKIE['lastlang'];
    return $ans;
}
function getGet(){
    global $languageName, $jresult;
    $ans="";
    if (isset($_GET['cid'])){
        $cid=intval($_GET['cid']);
        $ans=$ans."&cid=$cid";
    }
    $prid=getProblemId();
    if($prid!="") $ans=$ans."&problem_id=".$prid;
    
    $usid=getUserId();
    if ($usid!="") $ans.="&user_id=".$usid;
    
    $lan=getLanguage($languageName);
    if($lan!=-1) $ans=$ans."&language=".$lan;

    /// En bolas que signifique el lock
    $lock = false;
    $lock_time = date("Y-m-d H:i:s", time());
    $sql = "SELECT * FROM `solution` WHERE problem_id>0 ";
    if (isset($_GET['cid'])) {
        $cid        = intval($_GET['cid']);
        $sql        = $sql." AND `contest_id`='$cid' and num>=0 ";
        $sql_lock   = "SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
        $result     = mysql_query($sql_lock) or die(mysql_error());
        $rows_cnt   = mysql_num_rows($result);
        $start_time = 0;
        $end_time   = 0;
        if ($rows_cnt > 0) {
            $row        = mysql_fetch_array($result);
            $start_time = strtotime($row[0]);
            $title      = $row[1];
            $end_time   = strtotime($row[2]);
        }
        $lock_time = $end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
        if (time() > $lock_time && time() < $end_time) {
            $lock = true;
        } else {
            $lock = false;
        }
    }
    ////END en bolas
    $jres=getJresult($jresult);
    if ($jres!=-1 && !$lock) {
        $ans.="&jresult=".$jres;
    }

    $shs=getShowsim();
    if ($shs>0) $ans.="&showsim=$shs";

    return $ans;
}


function crearCodigoFuente(){
    global $language_name, $judge_result, $OJ_AUTO_SHARE;
    $id=strval(intval($_GET['id']));
    $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_object($result);
    mysql_free_result($result);
    if($row){        
        $ok=false;
        if ($OJ_AUTO_SHARE&&isset($_SESSION['user_id'])){
            $sql="SELECT 1 FROM solution where 
			result=4 and problem_id=".$row->problem_id." and user_id='".$_SESSION['user_id']."'";
            $acrow=mysql_query($sql);
            $ok=(mysql_num_rows($acrow)>0);
            mysql_free_result($acrow);
        }       
        if (((isset($_SESSION['user_id']) && $row->user_id==$_SESSION['user_id'])
             or (isset($_SESSION['source_browser']))
             or $ok==true )){
            $sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
            $result=mysql_query($sql);
            $source=mysql_fetch_object($result);        
            $view_source=$source->source;
            $view_source=str_replace("\r\n","\n",$view_source);
            $view_source=str_replace("\r","\n",$view_source);    
            $view_source=str_replace("\n","\\n",$view_source);
            $view_source=str_replace("\"","\\\"",$view_source);
            echo "dat.codigoOk=1;";
            echo "dat.codigoProblem=\"".$row->problem_id."\";";
            echo "dat.codigoUser=\"$row->user_id\";";
            echo "dat.codigoLang=\"".$language_name[$row->language]."\";";
            echo "dat.codigoResult=\"".$judge_result[$row->result]."\";";
            echo "dat.codigoSource=\"".$view_source."\";";
            if ($row->result!=4 || 1){ // acepted
                echo "dat.codigoTime=\"$row->time\";";
                echo "dat.codigoMem=\"$row->memory\";";
            }
            if($row->user_id!=$_SESSION['user_id'])
                echo "dat.linkMail=\"mail.php?to_user=$row->user_id&title='Envio No:$id'\";";
        }else{
            echo "dat.codigoOk=0;";
            echo "dat.codigoSource=\"No puedes ver este codigo!\";";    
        }
    }else{
        echo "dat.codigoOk=0;";
        echo "dat.codigoSource=\"No hay tal codigo!\";";
    }
    
}


?>
