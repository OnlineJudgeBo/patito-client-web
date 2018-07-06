<?php
require_once("include/db_info.inc.php");
function sec2str($sec){ //eliminar de temmplate/og/contestrank.php
	return sprintf("%02d:%02d:%02d",$sec/3600,$sec%3600/60,$sec%60);
}
//$languageName=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Other Language");
$jresult=Array($MSG_PD,$MSG_PR,$MSG_CI,$MSG_RJ,$MSG_AC,$MSG_PE,$MSG_WA,$MSG_TLE,$MSG_MLE,$MSG_OLE,$MSG_RE,$MSG_CE,$MSG_CO,$MSG_TR);
$PID=Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");
$judge_result=Array($MSG_Pending,$MSG_Pending_Rejudging,$MSG_Compiling,$MSG_Running_Judging,$MSG_Accepted,$MSG_Presentation_Error,$MSG_Wrong_Answer,$MSG_Time_Limit_Exceed,$MSG_Memory_Limit_Exceed,$MSG_Output_Limit_Exceed,$MSG_Runtime_Error,$MSG_Compile_Error,$MSG_Compile_OK,$MSG_TEST_RUN);
$judge_color=Array("gray","gray","orange","orange","green","red","red","red","red","red","red","navy ","navy");
$language_name = Array("C","C++","Pascal","Java","Ruby","Bash","Python2","PHP","Perl","C#","Obj-C","FreeBasic","Other Language","","","Python3","C++11");
$language_ext  = Array( "c", "cc", "pas"  ,"java", "rb" , "sh" ,"py"     ,"php","pl"  ,"cs","m"    ,"bas"      ,""              ,"","","py","cc" );
$sim_arr  = Array(10, 30, 50, 60, 70, 80, 90, 100);
//$language_name=Array("C","C++","Pascal","Java","Ruby","Bash","Python","PHP","Perl","C#","Obj-C","FreeBasic","Other Language");
//$language_ext=Array( "c", "cc", "pas", "java", "rb", "sh", "py", "php","pl", "cs","m","bas" );
//$language_ext=Array( "c", "cpp", "pas", "java", "rb", "sh", "python", "php","pl", "cs","m","bas" );
$view_title="Juez Virtual UMSA";

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
function check_ac($cid,$pid){
	require_once("./include/db_info.inc.php");
	$sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `result`='4' AND `user_id`='".$_SESSION['user_id']."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$ac=intval($row[0]);
	mysql_free_result($result);
	if ($ac>0) return 1;
	$sql="SELECT count(*) FROM `solution` WHERE `contest_id`='$cid' AND `num`='$pid' AND `user_id`='".$_SESSION['user_id']."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$sub=intval($row[0]);
	mysql_free_result($result);
	if ($sub>0) return 0;
	else return -1;
}
function is_running($cid){
	require_once("./include/db_info.inc.php");
    $now=strftime("%Y-%m-%d %H:%M",time());
	$sql="SELECT count(*) FROM `contest` WHERE `contest_id`='$cid' AND `end_time`>'$now'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$cnt=intval($row[0]);
	mysql_free_result($result);
	return $cnt>0;
}
function is_valid_user_name($user_name){
	$len=strlen($user_name);
	for ($i=0;$i<$len;$i++){
		if (
			($user_name[$i]>='a' && $user_name[$i]<='z') ||
			($user_name[$i]>='A' && $user_name[$i]<='Z') ||
			($user_name[$i]>='0' && $user_name[$i]<='9') ||
			$user_name[$i]=='_'||
			($i==0 && $user_name[$i]=='*') 
		);
		else return false;
	}
	return true;
}
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

function formatTimeLength($length){
 	$hour = 0;
 	$minute = 0;
 	$second = 0;
 	$result = '';
 	if ($length >= 60){
 		$second = $length % 60;
 		if ($second > 0){
 			$result = $second . 'seg ';
 		}
 		$length = floor($length / 60);
 		if ($length >= 60){
 			$minute = $length % 60;
 			if ($minute == 0){
 				if ($result != ''){
 					$result = '0minuto ' . $result;
 				}
 			}
 			else{
 				$result = $minute . 'min ' . $result;
 			}
 			$length = floor($length / 60);
 			if ($length >= 24){
 				$hour = $length % 24;
 				if ($hour == 0){
 					if ($result != ''){
 						$result = '0hora ' . $result;
 					}
 				}
 				else{
 					$result = $hour . 'horas ' . $result;
 				}
 				$length = floor($length / 24);
 				$result = $length . 'dias ' . $result;
 			}
 			else{
 				$result = $length . 'hora' . $result;
 			}
 		}
 		else{
 			$result = $length . 'min ' . $result;
 		}
 	}
 	else{
 		$result = $length . 'segundo ';
 	}
 	return $result;
}

function checkcontest(){
    require_once("./include/db_info.inc.php");
    $now=strftime("%Y-%m-%d %H:%M",time());
    $sql="SELECT count(*) FROM `contest` WHERE `end_time`>'$now' AND `defunct`='N'";
    $result=mysql_query($sql);
    $row=mysql_fetch_row($result);
    if (intval($row[0])==0) $retmsg="";
    else $retmsg=$row[0];
    mysql_free_result($result);
    return $retmsg;
}
function firstPro(){return 1000;}
function numProbPag(){return 50;}
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
function getProblemId(){ // check the problem arg
    global $PID;
    if (!isset($_GET['problem_id'])) return -1;
    if(!isset($_GET['cid'])){
        return intval($_GET['problem_id']);
    }
    $problem_id=$_GET['problem_id'];
    if(in_array($problem_id, $PID)){
        return array_search($problem_id, $PID);
    }
    return -1;
}
function getUser(){
    return isset($_GET['user'])?$_GET['user']:"";
}
function getScope(){
    return isset($_GET['scope'])?$_GET['scope']:"";
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
    global $language_name;
    $lan=-1;
    if (isset($_GET['language'])) $lan=intval($_GET['language']);
    if($lan<0||$lan>=count($language_name)) $lan=-1;
    return $lan;
}

function getJresult(){
    global $jresult;
    $jres=-1;
    if (isset($_GET['jresult'])) $jres=intval($_GET['jresult']);
    if($jres<0||$jres>=count($jresult)) $jres=-1;
    return $jres;
}
function getShowsim(){
    global $OJ_SIM, $sim_arr;
    $ss=-1;
    if($OJ_SIM) if (isset($_GET['showsim'])) $ss=intval($_GET['showsim']);
    if($ss<0 || $ss>=count($sim_arr)) $ss=-1;
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
    global $languageName, $jresult, $OJ_RANK_LOCK_PERCENT;
    $ans="";
    if (isset($_GET['cid'])){
        $cid=intval($_GET['cid']);
        $ans=$ans."&cid=$cid";
    }
    $prid=getProblemId();
    if($prid!="") $ans=$ans."&problem_id=".$prid;
    
    $usid=getUserId();
    if ($usid!="") $ans.="&user_id=".$usid;
    
    $lan=getLanguage();
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
    $jres=getJresult();
    if ($jres!=-1 && !$lock) {
        $ans.="&jresult=".$jres;
    }
    $shs=getShowsim();
    if ($shs!=-1) $ans.="&showsim=$shs";
    return $ans;
}

function crearlistContest(){
    echo "var listContest=[];";
    $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
    $result=mysql_query($sql);
    $view_contest=Array();
    while ($row=mysql_fetch_object($result)){
        $fecha=date("Y-m-d H:i:s");
        //if (!isset($_SESSION['administrator']) && intval($row->private)!=0) continue;
        if (time()>strtotime($row->end_time)) continue;
        $order=array("\r\n", "\n", "\r");
        $titulo=str_replace($order, "\\n", $row->title);
        $titulo=str_replace("\"", "\\\"", $titulo);
        echo "listContest.push({id:$row->contest_id, title:\"$titulo\", start:\"$row->start_time\", end:\"$row->end_time\", now:\"$fecha\", tipo:\"$row->private\"});";
    }
    mysql_free_result($result);
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

function imprimirProb($row, $pid, $cid, $root){
    global $PID, $MSG_PROBLEM;
    echo "$root={};";
    echo "$root.id=$row->problem_id;";
    echo "$root.title=\"$row->title\";";
    if($pid==-1)
        echo "$root.showTitle=\"$row->title\";";
    else{
        echo "$root.showTitle=\"$MSG_PROBLEM $PID[$pid]: $row->title\";";
        echo "$root.pId=$pid;";
        echo "$root.cId=$cid;";
    }
    echo "$root.time=\"$row->time_limit\";";
    echo "$root.mem=\"$row->memory_limit\";";
    echo "$root.submit=\"$row->submit\";";
    echo "$root.ac=\"$row->accepted\";";
    echo "$root.spj=\"$row->spj\";";
    echo "$root.des=".json_encode($row->description).";";
    echo "$root.input=".json_encode($row->input).";";
    echo "$root.output=".json_encode($row->output).";";
    echo "$root.sinput=".json_encode($row->sample_input).";";
    echo "$root.soutput=".json_encode($row->sample_output).";";
    echo "$root.hint=".json_encode($row->hint).";";
    echo "$root.source=\"$row->source\";\n";
}
function problem(){
    global $OJ_TEMPLATE, $MSG_PROBLEM, $PID, $MSG_PROBLEMS, $view_title;
    $now=strftime("%Y-%m-%d %H:%M",time());
    if (isset($_GET['id'])){//practice
        $id=intval($_GET['id']);
        $result=mysql_query("SELECT * FROM problem WHERE problem_id=$id");
        if (mysql_num_rows($result)==0){
            echo "dat.error=\"<h3>No existe tal problema!..</h3>\";";
            return ;
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
        if (mysql_num_rows($result)==0){
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
            return ;
        }
        $row=mysql_fetch_object($result);
        $view_title= $row->title;
        mysql_free_result($result);
        imprimirProb($row, -1, -1, "dat.problem");
    }else{
        $view_title=$MSG_PROBLEMS;
        crearTablaProblemSet();
    }
}
function crearTablaProblemSet(){ // mas datos 
    global $MSG_PROBLEM_ID,$MSG_TITLE,$MSG_SOURCE,$MSG_AC;
    // sub_arry vector donde [problem_id] esta en true si le acepto y false si no 
    $subArr=arrSub();
    $pstart=firstPro()+numProbPag()*intval(page())-numProbPag();
    $pend=$pstart+numProbPag();
    if(isset($_GET['search'])&&trim($_GET['search'])!=""){
        $search=mysql_real_escape_string($_GET['search']);
        $filter_sql=" ( title like '%$search%' or source like '%$search%')";
        echo "dat.totalPage=0;";
    }else{
        $filter_sql="`problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
        echo "dat.totalPage= ".numPagProbset().";";
        echo "dat.page=".page().";";
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
    echo "dat.problemSet={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}, problem:[]};";
    echo "dat.problemSet.tabla.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"$MSG_TITLE\"},{text:\"$MSG_SOURCE\"},{text:\"$MSG_AC\"},{text:\"Envios\"});";
    $i=0;
    while ($row=mysql_fetch_object($result)){
        echo "dat.problemSet.tabla.body.rows.push({props:{},row:[{},{},{},{},{}]});\n";
        echo "dat.problemSet.problem.push({});\n";
        echo "dat.problemSet.tabla.body.rows[$i].props.bgColor=\"\";\n";
        if (isset($subArr[$row->problem_id])){
            if ($subArr[$row->problem_id]){
                echo "dat.problemSet.tabla.body.rows[$i].props.bgColor=\"green lighten-2\";";
            }else{
                echo "dat.problemSet.tabla.body.rows[$i].props.bgColor=\"red lighten-2\";";
            }
        }
        echo "dat.problemSet.tabla.body.rows[$i].row[0].text=$row->problem_id;";
        echo "dat.problemSet.tabla.body.rows[$i].row[0].link=\"#\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[0].click=$i;";
        echo "dat.problemSet.tabla.body.rows[$i].row[0].textAlign=\"center\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[1].text=\"$row->title\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[1].link=\"#\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[1].click=$i;";
        echo "dat.problemSet.tabla.body.rows[$i].row[2].text=\"$row->source\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[2].link=\"userinfo.php?user=$row->source\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[2].textAlign=\"center\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[3].text=$row->accepted;";
        echo "dat.problemSet.tabla.body.rows[$i].row[3].link=\"status.php?id=$row->problem_id&jresult=4\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[3].textAlign=\"center\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[4].text=$row->submit;";
        echo "dat.problemSet.tabla.body.rows[$i].row[4].link=\"status.php?id=$row->problem_id\";";
        echo "dat.problemSet.tabla.body.rows[$i].row[4].textAlign=\"center\";";
        imprimirProb($row, -1, -1, "dat.problemSet.problem[$i]");
        $i++;
    }
    mysql_free_result($result);  
}
$top;
$bottom;
function crearTablaStatus(){
    global $MSG_Manual, $MSG_AC, $MSG_WA, $jresult, $PID, $judge_result, $judge_color,
        $language_name, $MSG_Explain, $MSG_OK, $top, $bottom, $OJ_SIM, $OJ_MEMCACHE,
        $OJ_SHOW_DIFF, $MSG_RUNID, $MSG_USER, $MSG_PROBLEM, $MSG_RESULT, $MSG_MEMORY,
        $MSG_TIME, $MSG_LANG, $MSG_CODE_LENGTH, $MSG_SUBMIT_TIME, $OJ_RANK_LOCK_PERCENT, $sim_arr;/// TOP Corregir
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
    } else {
        if (isset($_SESSION['administrator']) || isset($_SESSION['source_browser']) ||
            (isset($_SESSION['user_id']) && isset($_GET['user_id']) && $_GET['user_id'] == $_SESSION['user_id'])) {
            if ($_SESSION['user_id'] != "guest") {
                //	$sql = "SELECT * FROM `solution` WHERE contest_id is null "; //cambio ultimo pedido lic_teran
            }
        } else {
            $sql = "SELECT * FROM `solution` WHERE problem_id>0 and contest_id is not null ";
        }
    }
    //echo $sql;
    $start_first = true;
    $order_str   = " ORDER BY `solution_id` DESC ";
    // check the top arg
    if (isset($_GET['top'])) {
        $top                  = strval(intval($_GET['top']));
        if ($top != -1) {$sql = $sql."AND `solution_id`<='".$top."' ";}
    }
    // check the problem arg OG ////////OJOJOJOJ
    $problem_id=getProblemId();
    if ($problem_id!=-1) {
        if (isset($_GET['cid'])) {
            $num=$problem_id;
            $sql=$sql."AND num='".$num."' ";
        } else {
            $sql.="AND problem_id='".$problem_id."' "; //aquise usa $problem_id;
        }
    }
    $user_id=getUserId();
    if ($user_id != "") $sql.="AND `user_id`='".$user_id."' ";

    $language=getLanguage();
    if ($language != -1) $sql.="AND `language`='".strval($language)."' ";

    $result=getJresult();
    if ($result != -1 && !$lock) $sql.="AND `result`='".strval($result)."' ";
    //echo $sql;
    if ($OJ_SIM) {
        $old = $sql;
        $sql = "select * from ($sql order by solution_id desc limit 1000) solution left join `sim` on solution.solution_id=sim.s_id WHERE 1 ";
        $showsim = getShowsim();
        if ($showsim!=-1) {
            $sql     = "select * from ($old ) solution
          left join `sim` on solution.solution_id=sim.s_id WHERE result=4 and sim>=$sim_arr[$showsim] limit 1000";
            $sql = "SELECT * FROM ($sql) solution ".
                 "left join(select solution_id old_s_id,user_id old_user_id from solution limit 1000) old ".
                 "on old.old_s_id=sim_s_id WHERE  old_user_id!=user_id and sim_s_id!=solution_id ";
            //echo $sql;
        }
        //$sql=$sql.$order_str." LIMIT 20";
    }
    $sql = $sql.$order_str." LIMIT 23";    
    ///termina de armarse $sql********************************************************
    if ($OJ_MEMCACHE) {
        require ("./include/memcache.php");
        $result = mysql_query_cache($sql);
        // or die("Error! ".mysql_error());
        if ($result) {$rows_cnt = count($result);
        } else { $rows_cnt = 0; }
    } else {
        $result = mysql_query($sql);
        // or die("Error! ".mysql_error());

        if ($result) {$rows_cnt = mysql_num_rows($result);
        } else { $rows_cnt = 0; }
    }

    $top = $bottom = -1;
    if ($start_first) {
        $row_start = 0;
        $row_add   = 1;
    } else {
        $row_start = $rows_cnt-1;
        $row_add   = -1;
    }
    echo "var TablaS={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    echo "TablaS.head.row.push({text:\"$MSG_RUNID\"},{text:\"$MSG_USER\"},{text:\"$MSG_PROBLEM\"},{text:\"$MSG_RESULT\"},{text:\"$MSG_MEMORY\"},{text:\"$MSG_TIME\"},{text:\"$MSG_LANG\"},{text:\"$MSG_CODE_LENGTH\"},{text:\"$MSG_SUBMIT_TIME\"});";
    if (isset($_SESSION['http_judge'])) {
        echo "TablaS.head.row.push({text:\"Juzgar Manual\"});";
    }
    $last = 0;
    for ($i=0, $j=0; $j<$rows_cnt; $i++, $j++) {
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
        
        if(isset($_GET['showsim']) && $_GET['showsim']!=-1){
            if($row['sim']<$sim_arr[$_GET['showsim']]){
                $i--;
                continue;
                
            }
        }
        echo "TablaS.body.rows.push({props:{},row:[{},{},{},{},{},{},{},{},{}]});\n";        
        echo "TablaS.body.rows[$i].row[0].text=".$row['solution_id'].";";
        echo "TablaS.body.rows[$i].row[0].textAlign=\"center\";";
        if ( (isset($_SESSION['user_id']) && strtolower($row['user_id']) == strtolower($_SESSION['user_id'])) || isset($_SESSION['source_browser']) ) {
            echo "TablaS.body.rows[$i].row[0].link=\"showsource.php?id=".$row['solution_id']."\";";
        }            
        echo "TablaS.body.rows[$i].row[1].text=\"".$row['user_id']."\";";
        echo "TablaS.body.rows[$i].row[1].textAlign=\"center\";";
        if ($row['contest_id'] > 0) {
            echo "TablaS.body.rows[$i].row[1].link=\"contestrank.php?cid=".$row['contest_id']."&user_id=".$row['user_id']."#".$row['user_id']."\";";            
        } else {
            echo "TablaS.body.rows[$i].row[1].link=\"userinfo.php?user=".$row['user_id']."\";";
        }
        echo "TablaS.body.rows[$i].row[2].textAlign=\"center\";";
        if ($row['contest_id'] > 0) {
            echo "TablaS.body.rows[$i].row[2].link=\"problem.php?cid=".$row['contest_id']."&pid=".$row['num']."\";";
            if (isset($cid)) {
                echo "TablaS.body.rows[$i].row[2].text=\"".$PID[$row['num']]."\";";
            } else {
                echo "TablaS.body.rows[$i].row[2].text=\"".$row['problem_id']."\";";
            }
        } else {
            echo "TablaS.body.rows[$i].row[2].link=\"problem.php?id=".$row['problem_id']."\";";
            echo "TablaS.body.rows[$i].row[2].text=\"".$row['problem_id']."\";";
        }
        echo "TablaS.body.rows[$i].row[3].text=\"".$judge_result[$row['result']]."\";\n";
        echo "TablaS.body.rows[$i].row[3].textAlign=\"center\";\n";        
        if (isset($_SESSION['user_id'])){
            if($row['user_id']==$_SESSION['user_id']){
                if($row['result']==4) echo "TablaS.body.rows[$i].props.bgColor=\"green\";";
                if($row['result']==5) echo "TablaS.body.rows[$i].props.bgColor=\"orange\";";
                if($row['result']>=6 and $row['result']<=11) echo "TablaS.body.rows[$i].props.bgColor=\"red\";";
            }
        }else{
            if($row['result']==4) echo "TablaS.body.rows[$i].row[3].ctext=\"green-text\";";
            if($row['result']==5) echo "TablaS.body.rows[$i].row[3].ctext=\"orange-text\";";
            if($row['result']>=6 and $row['result']<=11) echo "TablaS.body.rows[$i].row[3].ctext=\"red-text\";";
            if($row['result']==7) echo "TablaS.body.rows[$i].row[5].ctext=\"red-text\";";
            if($row['result']==8) echo "TablaS.body.rows[$i].row[4].ctext=\"red-text\";";
            if($row['result']==10)echo "TablaS.body.rows[$i].row[7].ctext=\"red-text\";";
            if($row['result']==11 || $row['result']==10)echo "TablaS.body.rows[$i].row[6].ctext=\"red-text\";";
        }
        if (intval($row['result']) == 11 && ((isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) || isset($_SESSION['source_browser']))) {
            echo "TablaS.body.rows[$i].row[3].link=\"ceinfo.php?sid=".$row['solution_id']."\";";
        } else
            if (((intval($row['result']) == 6 && $OJ_SHOW_DIFF) || $row['result'] == 10 || $row['result'] == 13) && ((isset($_SESSION['user_id']) && $row['user_id'] == $_SESSION['user_id']) || isset($_SESSION['source_browser']))) {
                echo "TablaS.body.rows[$i].row[3].link=\"reinfo.php?sid=".$row['solution_id']."\";";
            } else {
                if (!$lock || $lock_time > $row['in_date'] || $row['user_id'] == $_SESSION['user_id']) {                    
                    if ($OJ_SIM && $row['sim_s_id']!=$row['s_id'] && ( (isset($_GET['showsim']) && $_GET['showsim']!=-1) || ($row['sim']>80) ) ) {
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
        if (isset($_SESSION['http_judge'])) {
            echo "TablaS.body.rows[$i].row.push({});";
            echo "TablaS.body.rows[$i].row[9].textAlign=\"center\";";
            echo "TablaS.body.rows[$i].row[9].text=\"<div style='width:370px; align:center;' class='input-field'>"
                ."<div class='row'><form method=post action='admin/problem_judge.php'>"
                ."<input type='hidden' name='sid' value='".$row['solution_id']."'>\";";            
            echo "TablaS.body.rows[$i].row[9].text+=\"<select class='btn input-small' "
                ."length=2 name=result style='width:70px;'>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='0'>RJ</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='4'>$MSG_AC</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<option value='6'>$MSG_WA</option>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"</select>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<button class=' waves-effect' "
                ."style='width:50px;' type='submit' name='manual'>OK</button>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"<span class='input-field'><input class='input-small' style='width:150px;' "
                ."title='$MSG_Explain' type='text' id='explainID' name='explain'>"
                ."<label for='explainID'>Explicacion....</label><span>\";";
            echo "TablaS.body.rows[$i].row[9].text+=\"</form></div></div>\";";
        }
        echo "TablaS.body.rows[$i].row[4].textAlign=\"center\";";
        echo "TablaS.body.rows[$i].row[5].textAlign=\"center\";";
        echo "TablaS.body.rows[$i].row[6].textAlign=\"center\";";
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
    if (!$OJ_MEMCACHE) {mysql_free_result($result);}
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
            echo "dat.codigoOk=1;";
            echo "dat.codigoProblem=\"".$row->problem_id."\";";
            echo "dat.codigoUser=\"$row->user_id\";";
            echo "dat.codigoLang=\"".$language_name[$row->language]."\";";
            echo "dat.codigoResult=\"".$judge_result[$row->result]."\";";
            echo "dat.codigoSource=".json_encode($view_source).";";
            if ($row->result!=4 || 1){ // acepted
                echo "dat.codigoTime=\"$row->time\";";
                echo "dat.codigoMem=\"$row->memory\";";
            }
            if($row->user_id!=$_SESSION['user_id'])
                echo "dat.linkMail=\"mail.php?to_user=$row->user_id&title='Envio No:$id'\";";
        }else{
            echo "dat.error=\"<h3>No puedes ver este codigo!...</h3>\";";    
        }
    }else{
        echo "dat.error=\"<h3>No hay tal codigo!...</h3>\";";
    }    
}

function crearTablaContestSet(){
    global $MSG_Start, $MSG_TotalTime, $MSG_Public, $MSG_Running, $MSG_LeftTime, $MSG_Private, $MSG_Ended, $MSG_PROBLEM_ID;
    $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
	$result=mysql_query($sql);
	$i=0;
    echo "dat.contestSet={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
    echo "dat.contestSet.tabla.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"Nombre\"},{text:\"Estado\"},{text:\"Tipo\"});";
	while ($row=mysql_fetch_object($result)){
        echo "dat.contestSet.tabla.body.rows.push({props:{},row:[{},{},{},{}]});\n";
        echo "dat.contestSet.tabla.body.rows[$i].row[0].text=$row->contest_id;";
        echo "dat.contestSet.tabla.body.rows[$i].row[0].link=\"contest.php?cid=$row->contest_id\";";
        echo "dat.contestSet.tabla.body.rows[$i].row[0].textAlign=\"center\";";
        echo "dat.contestSet.tabla.body.rows[$i].row[1].text=\"$row->title\";";
        echo "dat.contestSet.tabla.body.rows[$i].row[1].link=\"contest.php?cid=$row->contest_id\";";
		$start_time=strtotime($row->start_time);
		$end_time=strtotime($row->end_time);
		$now=time();
		$length=$end_time-$start_time;
		$left=$end_time-$now;
		if ($now>$end_time) {// past
            echo "dat.contestSet.tabla.body.rows[$i].row[2].text=\"$MSG_Ended@$row->end_time\";";			
		}else if ($now<$start_time){// pending
            echo "dat.contestSet.tabla.body.rows[$i].row[2].text=\"$MSG_Start@$row->start_time$MSG_TotalTime"
                .formatTimeLength($length)."\";";
            echo "dat.contestSet.tabla.body.rows[$i].row[2].ctext=\"green-text\";";			
		}else{// running
            echo "dat.contestSet.tabla.body.rows[$i].row[2].text=\"$MSG_Running $MSG_LeftTime "
                .formatTimeLength($left)."\";";
            echo "dat.contestSet.tabla.body.rows[$i].row[2].ctext=\"red-text\";";
		}
        echo "dat.contestSet.tabla.body.rows[$i].row[2].textAlign=\"center\";";
		$private=intval($row->private);        
		if ($private==0){
            echo "dat.contestSet.tabla.body.rows[$i].row[3].text=\"$MSG_Public\";";
            echo "dat.contestSet.tabla.body.rows[$i].row[3].ctext=\"green-text\";";
        }else{
            echo "dat.contestSet.tabla.body.rows[$i].row[3].text=\"$MSG_Private\";";
            echo "dat.contestSet.tabla.body.rows[$i].row[3].ctext=\"red-text\";";
        }
        echo "dat.contestSet.tabla.body.rows[$i].row[3].textAlign=\"center\";";
		$i++;
	}
	mysql_free_result($result);
}

function crearDatosContest($cid){
    global $PID;
    $view_cid=$cid;
    $sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
    $result=mysql_query($sql);
    $rows_cnt=mysql_num_rows($result);
    $row=mysql_fetch_object($result);       
    $view_title=$row->title;
    $now=time();
    $start_time=strtotime($row->start_time);
    $end_time=strtotime($row->end_time);
    echo "dat.title=".json_encode($row->title).";";
    echo "dat.contest.start=\"$row->start_time\";";
    echo "dat.contest.end=\"$row->end_time\";";
    echo "dat.contest.title=".json_encode($row->title).";";
    echo "dat.contest.description=".json_encode($row->description).";";
    echo "dat.contest.private=\"$row->private\";";
    echo "dat.contest.now=\"".date("Y-m-d H:i:s")."\";";
    echo "dat.contest.id=\"".$cid."\";";
    //echo "holaaaa:::$row->langmask|||||||||||asfa";
    echo "dat.contest.langmask=\"$row->langmask\";";
    echo "dat.PID=[\"".implode("\",\"", $PID)."\"];";
}
function contest(){
    global $PID, $MSG_PROBLEM_ID, $MSG_TITLE, $MSG_SOURCE, $MSG_AC, $MSG_SUBMIT, $view_title, $MSG_CONTESTS, $MSG_PRIVATE_WARNING, $MSG_WATCH_RANK, $MSG_AC, $MSG_PE, $MSG_WA, $MSG_TLE, $MSG_MLE, $MSG_OLE, $MSG_RE, $MSG_CE;
    if (isset($_GET['cid'])){
        $cid=intval($_GET['cid']);
        $sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
        $result=mysql_query($sql); 	
        if (mysql_num_rows($result)==0){
            mysql_free_result($result);
            echo "dat.error=\"<h3>No hay tal contest!...</h3>\";";
            return ;
        }
        if(!isset($_SESSION['administrator'])){
            $contest_ok=true;
            $row=mysql_fetch_object($result);
            if ($row->private && !isset($_SESSION['c'.$cid]))$contest_ok=false;
            if ($row->defunct=='Y') $contest_ok=false;
            if (time()<strtotime($row->start_time)){
                echo "dat.error=\"<h3>Recien iniciara el concurso!...</h3>".$row->start_time."\";";
                return ;
            }
            if (!$contest_ok){                
                echo "dat.contest={onlyContest:1};";
                crearDatosContest($cid);
                crearContestRank();
                //echo "dat.error=\"<h3>$MSG_PRIVATE_WARNING <a href=contestrank.php?cid=$cid>".
                //$MSG_WATCH_RANK."</a></h3>\";";
                return ;
            }
        }
        $row=mysql_fetch_object($result);
    }
    if(isset($_GET['cid'])){
        $cid=getCid();
        $sql="select * from (SELECT problem.problem_id as problem_id, problem.title as title, problem.description as description, problem.input as input, problem.output as output, problem.sample_input as sample_input, problem.sample_output as sample_output, problem.spj as spj, problem.hint as hint, problem.source as source, problem.time_limit as time_limit, problem.memory_limit as memory_limit, problem.submit as submit, problem.accepted as accepted, `contest_problem`.`num` as pnum
 		FROM `contest_problem`,`problem`
 		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` AND `problem`.`defunct`='N'
 		AND `contest_problem`.`contest_id`=$cid
 		) problem
 left join (select problem_id pid1,count(1) accepted from solution where result=4 and contest_id=$cid group by pid1) p1 on problem.problem_id=p1.pid1
 left join (select problem_id pid2,count(1) submit from solution where contest_id=$cid  group by pid2) p2 on problem.problem_id=p2.pid2
 order by pnum
	";
        echo "dat.contest={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}, problem:[], statistics:{tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}}};\n";
        echo "dat.contest.tabla.head.row.push({text:\"$MSG_PROBLEM_ID\"},{text:\"$MSG_TITLE\"},{text:\"$MSG_SOURCE\"},{text:\"$MSG_AC\"},{text:\"$MSG_SUBMIT\"});";
        $result=mysql_query($sql);// or die(mysql_error());
        //echo $result;
        $view_problemset=Array();
        $i=0;
        while ($row=mysql_fetch_object($result)){
            echo "dat.contest.tabla.body.rows.push({props:{},row:[{},{},{},{},{}]});\n";
            echo "dat.contest.problem.push({});\n";
            $view_problemset[$i][0]="";
            if (isset($_SESSION['user_id'])){
                if(check_ac($cid,$i)==1) echo "dat.contest.tabla.body.rows[$i].props.bgColor=\"green\";";
                if(check_ac($cid,$i)==0) echo "dat.contest.tabla.body.rows[$i].props.bgColor=\"red lighten-2\";";
            }
            echo "dat.contest.tabla.body.rows[$i].row[0].text=\"Problema $PID[$i] ($row->problem_id)\";";
            echo "dat.contest.tabla.body.rows[$i].row[0].link=\"#\";";
            echo "dat.contest.tabla.body.rows[$i].row[0].click=$i;";
            echo "dat.contest.tabla.body.rows[$i].row[0].textAlign=\"center\";\n";
            echo "dat.contest.tabla.body.rows[$i].row[1].text=\"$row->title\";";
            echo "dat.contest.tabla.body.rows[$i].row[1].link=\"#\";";
            echo "dat.contest.tabla.body.rows[$i].row[1].click=$i;";
            echo "dat.contest.tabla.body.rows[$i].row[1].textAlign=\"center\";\n";
            echo "dat.contest.tabla.body.rows[$i].row[2].text=\"$row->source\";";
            echo "dat.contest.tabla.body.rows[$i].row[2].link=\"userinfo.php?user=$row->source\";";
            echo "dat.contest.tabla.body.rows[$i].row[2].textAlign=\"center\";\n";
            echo "dat.contest.tabla.body.rows[$i].row[3].text=".intval($row->accepted).";";
            echo "dat.contest.tabla.body.rows[$i].row[3].link=\"status.php?cid=$cid&problem_id=$PID[$i]&jresult=4\";";
            echo "dat.contest.tabla.body.rows[$i].row[3].textAlign=\"center\";\n";
            echo "dat.contest.tabla.body.rows[$i].row[4].text=".intval($row->submit).";";
            echo "dat.contest.tabla.body.rows[$i].row[4].link=\"status.php?cid=$cid&problem_id=$PID[$i]\";";
            echo "dat.contest.tabla.body.rows[$i].row[4].textAlign=\"center\";\n";
            imprimirProb($row, $i, $cid, "dat.contest.problem[$i]");
            $i++;
        }        
        mysql_free_result($result);
        // DATOS
        crearDatosContest($cid);
        crearContestRank();
        //////////////////.************************STATISTICS******************

        $sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $pid_cnt=intval($row[0]);
        mysql_free_result($result);

        $sql="SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`='$cid' and num>=0"; 
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

        $sql="SELECT (UNIX_TIMESTAMP(end_time)-UNIX_TIMESTAMP(start_time))/100 FROM contest WHERE contest_id=$cid ";
        $result=mysql_query($sql);
        $view_userstat=array();
        if($row=mysql_fetch_array($result)){
            $res=$row[0];
        }
        mysql_free_result($result);

        $sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$cid'   group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_all= array();
        //echo $sql;
   
        while ($row=mysql_fetch_array($result)){
            $chart_data_all[$row['md']]=$row['c'];
        }
   
        $sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$cid' and result=4 group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_ac= array();
        //echo $sql;
   
        while ($row=mysql_fetch_array($result)){
            $chart_data_ac[$row['md']]=$row['c'];
        }
        echo "dat.contest.statistics.tabla.head.row.push({text:\"#\"},{text:\"$MSG_AC\"},{text:\"$MSG_PE\"},{text:\"$MSG_WA\"},{text:\"$MSG_TLE\"},{text:\"$MSG_MLE\"},{text:\"$MSG_OLE\"},{text:\"$MSG_RE\"},{text:\"$MSG_CE\"},{text:\"Total\"},{text:\"C\"},{text:\"C++\"},{text:\"Pascal\"},{text:\"Java\"},{text:\"Ruby\"},{text:\"Bash\"},{text:\"Python\"},{text:\"PHP\"},{text:\"Perl\"},{text:\"C#\"},{text:\"Obj-c\"},{text:\"FreeBasic\"},{});";

        for ($i=0;$i<$pid_cnt;$i++){
            echo "dat.contest.statistics.tabla.body.rows.push({props:{},row:[{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}]});\n";
            echo "dat.contest.statistics.tabla.body.rows[$i].row[0]={text:\"$PID[$i]\", click:$i, textAlign:\"center\"};";
            //echo "<a href='problem.php?cid=$cid&pid=$i'>$PID[$i]</a>";
            for ($j=0;$j<22;$j++) {
                if(isset($R[$i][$j]))
                    echo "dat.contest.statistics.tabla.body.rows[$i].row[".($j+1)."]={text:".$R[$i][$j].", textAlign:\"center\"};";
                else
                    echo "dat.contest.statistics.tabla.body.rows[$i].row[".($j+1)."]={text:0, textAlign:\"center\"};";
                //  echo "<td>".$R[$i][$j];
            }
            //echo "</tr>";
        }
        echo "dat.contest.statistics.graphics={d1:[], d2:[], labels:[]};\n";
        foreach($chart_data_all as $k=>$d){
            echo "dat.contest.statistics.graphics.labels.push(new Date($k).toLocaleString());";
            echo "dat.contest.statistics.graphics.d1.push({x:new Date($k).toLocaleString(), y:$d});";
        }
        foreach($chart_data_ac as $k=>$d){		
            echo "dat.contest.statistics.graphics.d2.push({x:new Date($k).toLocaleString(), y:$d});";
        }
        //////////////////.************************STATISTICS******************
        mysql_free_result($result);

    }else{
        $view_title=$MSG_CONTESTS;
        echo "dat.title=\"$view_title\";";
        crearTablaContestSet();
    }
    
}

function crearTablaRanklist(){
    global $MSG_Number, $MSG_USER, $MSG_NICK, $MSG_AC, $MSG_SUBMIT, $MSG_RATIO, $MSG_RANKLIST, $OJ_MEMCACHE,
        $view_total,$page_size, $view_title;
    echo "var TablaR={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    echo "TablaR.head.row.push({text:\"$MSG_Number\"},{text:\"$MSG_USER\"},{text:\"$MSG_NICK\"},{text:\"$MSG_AC\"},{text:\"$MSG_SUBMIT\"},{text:\"$MSG_RATIO\"});";
    $view_title= $MSG_RANKLIST;
    $scope="";
    if(isset($_GET['scope'])) $scope=$_GET['scope'];
    if($scope!=""&&$scope!='d'&&$scope!='w'&&$scope!='m') $scope='y';

    $rank = 0;
    if(isset( $_GET ['start'] ))
        $rank = intval ( $_GET ['start'] );

    $page_size=50;
    //$rank = intval ( $_GET ['start'] );
    if ($rank < 0) $rank = 0;

    $sql = "SELECT `user_id`,`nick`,`solved`,`submit` FROM `users` ORDER BY `solved` DESC,submit,reg_time  LIMIT  " . strval ( $rank ) . ",$page_size";
    //echo "$scope";
    if($scope){
        $s="";
        switch ($scope){
        case 'd':
            $s=date('Y').'-'.date('m').'-'.date('d');
            break;
        case 'w':
            $monday=mktime(0, 0, 0, date("m"),date("d")-(date("w")+7)%8+1, date("Y"))                                                            ;
            //$monday->subDays(date('w'));
            $s=strftime("%Y-%m-%d",$monday);
            break;
        case 'm':
            $s=date('Y').'-'.date('m').'-01';
            ;break;
        default :
            $s=date('Y').'-01-01';
        }
        //echo $s."<-------------------------";
        $sql="SELECT users.`user_id`,`nick`,s.`solved`,t.`submit` FROM `users`
                                        right join
                                        (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc limit " . strval ( $rank ) . ",$page_size) s on users.user_id=s.user_id
                                        left join
                                        (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc limit " . strval ( $rank ) . ",".($page_size*2).") t on users.user_id=t.user_id
                                ORDER BY s.`solved` DESC,t.submit,reg_time  LIMIT  0,50
                         ";
        //                      echo $sql;
    }


    //         $result = mysql_query ( $sql ); //mysql_error();
    if($OJ_MEMCACHE){
        require("./include/memcache.php");
        $result = mysql_query_cache($sql) ;//or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
    }else{

        $result = mysql_query($sql) or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
    }
    $view_rank=Array();
    for ( $i=0;$i<$rows_cnt;$i++ ) {
        if($OJ_MEMCACHE) $row=$result[$i];
        else $row=mysql_fetch_array($result);
        $rank ++;
        echo "TablaR.body.rows.push({props:{},row:[{},{},{},{},{},{}]});\n";
        echo "TablaR.body.rows[$i].row[0].text=$rank;";
        echo "TablaR.body.rows[$i].row[0].textAlign=\"center\";";
        echo "TablaR.body.rows[$i].row[0].link=\"userinfo.php?user=".$row['user_id']."\";";
        echo "TablaR.body.rows[$i].row[1].text=\"".$row['user_id']."\";";
        echo "TablaR.body.rows[$i].row[1].link=\"userinfo.php?user=".$row['user_id']."\";";
        echo "TablaR.body.rows[$i].row[1].textAlign=\"center\";";
        echo "TablaR.body.rows[$i].row[2].text=\"".htmlspecialchars($row['nick'])."\";";
        echo "TablaR.body.rows[$i].row[2].link=\"userinfo.php?user=".$row['user_id']."\";";
        echo "TablaR.body.rows[$i].row[2].textAlign=\"center\";";
        echo "TablaR.body.rows[$i].row[3].text=".intval($row['solved']).";";
        echo "TablaR.body.rows[$i].row[3].link=\"status.php?user_id="
            .$row['user_id']."&jresult=4\";";
        echo "TablaR.body.rows[$i].row[3].textAlign=\"center\";";
        echo "TablaR.body.rows[$i].row[4].text=".intval($row['submit']).";";
        echo "TablaR.body.rows[$i].row[4].link=\"status.php?user_id=".$row['user_id']."\";";
        echo "TablaR.body.rows[$i].row[4].textAlign=\"center\";";
        if ($row['submit'] == 0){
            echo "TablaR.body.rows[$i].row[5].text=\"0.000%\";";
        }else{
            echo "TablaR.body.rows[$i].row[5].text=\""
                .sprintf("%.03lf%%", 100*$row['solved']/$row['submit'])."\";";
        }
        echo "TablaR.body.rows[$i].row[5].textAlign=\"center\";";
    }

    if(!$OJ_MEMCACHE)mysql_free_result($result);

    $sql = "SELECT count(1) as `mycount` FROM `users`";
    //        $result = mysql_query ( $sql );
    if($OJ_MEMCACHE){
        // require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
    }else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
    }
    if($OJ_MEMCACHE)
        $row=$result[0];
    else
        $row=mysql_fetch_array($result);
    echo mysql_error ();
    //$row = mysql_fetch_object ( $result );
    $view_total=$row['mycount'];

    //              mysql_free_result ( $result );

    if(!$OJ_MEMCACHE)  mysql_free_result($result);

}
//******************DISCUSSSSS PAGE *****************************

function crearContestRank(){
    global $MSG_CONTEST, $MSG_RANKLIST, $OJ_MEMCACHE, $cid, $title, $pid_cnt, $user_cnt, $U, $first_blood,$MSG_RANK, $MSG_USER, $MSG_NICK, $MSG_SOLVED, $MSG_PENALTY, $PID;
    $view_title= $MSG_CONTEST.$MSG_RANKLIST;
    $title="";
    require_once("./include/const.inc.php");
    //require_once("./include/my_func.inc.php");
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
        if (!isset($_GET['cid'])) die("No Such Contest!");
    $cid=intval($_GET['cid']);

    $sql="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
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

    $sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$cid'";
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
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
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
    echo "if(!(dat.contest)) dat.contest={};";
    echo "dat.contest.ranking={tabla:{props:{width:\"".(($pid_cnt*125)+500)."px\"}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
    //echo "dat.contest.ranking={tabla:{props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}}};";
    echo "dat.contest.ranking.tabla.head.row.push({text:\"$MSG_RANK\"},{text:\"$MSG_USER\"},{text:\"$MSG_NICK\"},{text:\"$MSG_SOLVED\"},{text:\"$MSG_PENALTY\"});";
    echo "var aux=[{},{},{},{},{},{}];";
    for ($i=0;$i<$pid_cnt;$i++){
        echo "dat.contest.ranking.tabla.head.row.push({text:\"$PID[$i]\",click:$i});";
            //."link:\"problem.php?cid=$cid&pid=$i\"});";
        echo "aux.push({});";
    }//echo "<td><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
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
        $sql="select user_id from solution where contest_id=$cid and result=4 and num=$i order by in_date limit 1";
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
        echo "dat.contest.ranking.tabla.body.rows.push({props:{},row:[{},{},{},{},{},{}]});\n";
        for ($j=0;$j<$pid_cnt;$j++) echo "dat.contest.ranking.tabla.body.rows[$i].row.push({});";
        $uuid=$U[$i]->user_id;
        $nick=$U[$i]->nick;
        if($rank<=6){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].borderLeftStyle=\"solid\";";
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].borderLeftWidth=\"25px\";";
        }
        if($rank==1){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].borderLeftColor=\"#FFD700\";";
            echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#FFD700\";";     
        }
        if($rank==2 || $rank==3){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].borderLeftColor=\"#C0C0C0\";";
            echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#C0C0C0\";";
        }
        if($rank>=4 && $rank<=6){
            //echo "dat.contest.ranking.tabla.body.rows[$i].row[0].borderLeftColor=\"#8C7853\";";
            echo "dat.contest.ranking.tabla.body.rows[$i].row[0].bgColorHTML=\"#8C7853\";";
        }
        if($nick[0]!="*"){
            echo "dat.contest.ranking.tabla.body.rows[$i].row[0].text=$rank;"; $rank++;
        }else echo "dat.contest.ranking.tabla.body.rows[$i].row[0].text=*;";
        
        echo "dat.contest.ranking.tabla.body.rows[$i].row[0].textAlign=\"center\";";
        $usolved=$U[$i]->solved;
        if(isset($_GET['user_id']))
            if($uuid==$_GET['user_id'])
                echo "dat.contest.ranking.tabla.body.rows[$i].row[1].bgcolor=\"red\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[1].text=\"$uuid\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[1].link=\"userinfo.php?user=$uuid\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[2].text=\"".$U[$i]->nick."\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[2].link=\"userinfo.php?user=$uuid\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[3].text=$usolved;";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[3].textAlign=\"center\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[3].link=\"status.php?user_id=$uuid&cid=$cid&jresult=4\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[4].text=\"".sec2str($U[$i]->time)."\";";
        echo "dat.contest.ranking.tabla.body.rows[$i].row[4].textAlign=\"center\";\n";
        for ($j=0;$j<$pid_cnt;$j++){
            $bg_color="eeeeee";
            if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
                if($U[$i]->p_wa_num[$j]==0) $bg_color="green lighten-1";
                if($U[$i]->p_wa_num[$j]>0) $bg_color="green lighten-2";
                if($U[$i]->p_wa_num[$j]>3) $bg_color="green lighten-3";
                if($U[$i]->p_wa_num[$j]>7) $bg_color="green lighten-4";
                if($uuid==$first_blood[$j]){
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].ctext=\"white-text\";";
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].borderBottomColor=\"#ffeb3b\";";
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].borderBottomStyle=\"solid\";";
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].borderBottomWidth=\"3px\";";
                    $bg_color="green accent-4";
                }
            }else if(isset($U[$i]->p_wa_num[$j])) {
                if($U[$i]->p_wa_num[$j]==1) $bg_color="red lighten-5";
                if($U[$i]->p_wa_num[$j]==2) $bg_color="red lighten-4";
                if($U[$i]->p_wa_num[$j]==3) $bg_color="red lighten-3";
                if($U[$i]->p_wa_num[$j]>3) $bg_color="red lighten-2";
                if($U[$i]->p_wa_num[$j]>5) $bg_color="red lighten-1";
            }
            echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].bgcolor=\"$bg_color\";";
            echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].textAlign=\"center\";\n";
            if(isset($U[$i])){
                echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].text=\"\";";
                if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].text+=\""
                                                     .sec2str($U[$i]->p_ac_sec[$j])."\";";
                if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0)
                    echo "dat.contest.ranking.tabla.body.rows[$i].row[".($j+5)."].text+=\"(-".
                                                     $U[$i]->p_wa_num[$j].")\";";
            }
        }
    }
    crearDatosContest($cid);									
}
function registerPage(){
    global $MSG_REG_INFO, $MSG_USER_ID, $MSG_NICK, $MSG_LASTNAME, $MSG_EMAIL, $MSG_COUNTRY, $MSG_INSTITUTE, $MSG_PASSWORD, $MSG_REPEAT_PASSWORD;
    echo "msg.regInfo=\"$MSG_REG_INFO\";";
    echo "msg.userId=\"$MSG_USER_ID\";";
    echo "msg.nick=\"$MSG_NICK\";";
    echo "msg.lastname=\"$MSG_LASTNAME\";";
    echo "msg.email=\"$MSG_EMAIL\";";
    echo "msg.country=\"$MSG_COUNTRY\";";
    echo "msg.institute=\"$MSG_INSTITUTE\";";
    echo "msg.password=\"$MSG_PASSWORD\";";
    echo "msg.repeatPassword=\"$MSG_REPEAT_PASSWORD\";";
    $sql = "SELECT * FROM pais order by usuarios";
    $data = mysql_query($sql);
    //$sel = " selected";
    echo "dat.paisArr={options:[], values:[]};";
    for ($i=0; $i <mysql_num_rows($data) ; $i++) { 
        //$retorno .="<option value='".mysql_result($data, $i,'id_pais')."'".$sel." >".utf8_decode(mysql_result($data, $i,'nombre'))."</option>";
        //$sel = "";
        echo "dat.paisArr.options.push(\"".utf8_decode(mysql_result($data, $i,'nombre'))."\");";
        echo "dat.paisArr.values.push(\"".mysql_result($data, $i,'id_pais')."\");\n";
    }
}

function userInfo(){
    global $Rank, $MSG_MAIL, $MSG_NUMBER, $MSG_SOLVED, $jresult;
    $user=$_GET['user'];
    if (!is_valid_user_name($user)){
        echo "dat.erro=\"</br></br></br><h3>Usuario no valido!...</h3></br></br></br>\";";
        return ;
    }
    $view_title=$user;
    $user_mysql=mysql_real_escape_string($user);
    $sql="SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`='$user_mysql'";
    $result=mysql_query($sql);
    $row_cnt=mysql_num_rows($result);
    if ($row_cnt==0){
        echo "dat.error=\"</br></br></br><h3>No hay tal usuario!...</h3></br></br></br>\";";
        return ;
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


    ///TABLA USER LOG

    echo "dat.user={};";
    echo "dat.user.tablaLogs={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    echo "dat.user.tablaLogs.head.row.push({text:\"UserID\"},{text:\"Password\"},{text:\"IP\"},{text:\"Time\"});";
    $user=getUser();
    $user_mysql=mysql_real_escape_string($user);
    if (isset($_SESSION['administrator'])){
        $sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_mysql' order by `time` desc LIMIT 0,10";
        $result=mysql_query($sql) or die(mysql_error());
        $view_userinfo=array();
        $i=0;
        for (;$row=mysql_fetch_row($result);){
            echo "dat.user.tablaLogs.body.rows.push({props:{},row:[{},{},{},{}]});\n";
            $view_userinfo[$i]=$row;
            echo "dat.user.tablaLogs.body.rows[$i].row[0].text=\"$row[0]\";";
            echo "dat.user.tablaLogs.body.rows[$i].row[1].text=\"$row[1]\";";
            echo "dat.user.tablaLogs.body.rows[$i].row[2].text=\"$row[2]\";";
            echo "dat.user.tablaLogs.body.rows[$i].row[3].text=\"$row[3]\";";
            $i++;
        }
        //echo "</table>";
        mysql_free_result($result);
    }
    // DATOS USER    
    echo "dat.user.id=\"$user\";";
    echo "dat.user.nick=\"$nick\";";
    echo "dat.user.school=\"$school\";";
    echo "dat.user.email=\"$email\";";
    echo "msg.mail=\"$MSG_MAIL\";";
    echo "msg.number=\"$MSG_NUMBER\";";
    echo "dat.user.rank=\"$Rank\";";
    echo "msg.solved=\"$MSG_SOLVED\";";
    echo "dat.user.ac=\"$AC\";";
    echo "dat.user.problemsAc=[];";
    $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id` ASC";	
    if (!($result=mysql_query($sql))) echo mysql_error();
    while ($row=mysql_fetch_array($result))
        echo "dat.user.problemsAc.push(".$row[0].");\n";
    mysql_free_result($result);
    echo "dat.user.submit=\"$Submit\";";
    echo "dat.user.tablaSubmit={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    echo "dat.user.tablaSubmit.head.row.push({text:\"N\"},{text:\"Tipo\"},{text:\"#\"});";
    echo "dat.user.tablaSubmit.body.rows.push({props:{},row:[{},{},{}]});\n";
    echo "dat.user.tablaSubmit.body.rows[0].row[0].text=0;";
    echo "dat.user.tablaSubmit.body.rows[0].row[1].text=\"Envios\";";
    echo "dat.user.tablaSubmit.body.rows[0].row[2].text=$Submit;";
    echo "dat.user.tablaSubmit.body.rows[0].row[2].link=\"status.php?user_id=$user\";";
    $i=1;
    foreach($view_userstat as $row){
        echo "dat.user.tablaSubmit.body.rows.push({props:{},row:[{},{},{}]});\n";
        echo "dat.user.tablaSubmit.body.rows[$i].row[0].text=$i;";
        echo "dat.user.tablaSubmit.body.rows[$i].row[1].text=\"".$jresult[$row[0]]."\";";
        echo "dat.user.tablaSubmit.body.rows[$i].row[2].text=".$row[1].";\n";
        echo "dat.user.tablaSubmit.body.rows[$i].row[2].link=\"status.php?user_id=$user&jresult=".$row[0]."\";";
        //echo "<tr bgcolor=#D7EBFF><td>".$jresult[$row[0]]."<td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a></tr>";
        $i++;
    }
    //echo "<tr bgcolor=#D7EBFF><td>".$jresult[$row[0]]."<td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a></tr>";
}
?>
