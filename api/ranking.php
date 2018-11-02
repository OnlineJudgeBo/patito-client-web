<?php
require_once('../include/db_info.inc.php');
if(isset($OJ_LANG))
    if(file_exists("../lang/$OJ_LANG.php"))require_once("../lang/$OJ_LANG.php");



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


if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

    $ranking = new stdClass();        
    $ranking->tabla = new stdClass();
    $ranking->tabla->props = new stdClass();
    $ranking->tabla->head = new stdClass();
    $ranking->tabla->head->props = new stdClass();
    $ranking->tabla->head->row = array();
    $ranking->tabla->body = new stdClass();
    $ranking->tabla->body->props = new stdClass();
    $ranking->tabla->body->rows = array();
    //echo "var TablaR={props:{}, head:{props:{}, row:[]}, body:{props:{}, rows:[]}};";
    //echo "TablaR.head.row.push({text:\"$MSG_Number\"},{text:\"$MSG_USER\"},{text:\"$MSG_NICK\"},{text:\"$MSG_AC\"},{text:\"$MSG_SUBMIT\"},{text:\"$MSG_RATIO\"});";
    array_push($ranking->tabla->head->row,["text"=>$MSG_Number], ["text"=>$MSG_USER],["text"=>$MSG_NICK],["text"=>$MSG_AC], ["text"=>$MSG_SUBMIT], ["text"=>$MSG_RATIO]);
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
        array_push($ranking->tabla->body->rows, (object)["props"=>new stdClass(), "row"=>array(new stdClass(),new stdClass(),new stdClass(),new stdClass(), new stdClass(), new stdClass())]);
        //echo "TablaR.body.rows.push({props:{},row:[{},{},{},{},{},{}]});\n";
        
        $ranking->tabla->body->rows[$i]->row[0]->text=$rank;
        $ranking->tabla->body->rows[$i]->row[0]->textAlign="center";
        $ranking->tabla->body->rows[$i]->row[0]->link="userinfo.php?user=".$row['user_id'];
        $ranking->tabla->body->rows[$i]->row[1]->text=$row['user_id'];
        $ranking->tabla->body->rows[$i]->row[1]->link="userinfo.php?user=".$row['user_id'];
        $ranking->tabla->body->rows[$i]->row[1]->textAlign="center";
        $ranking->tabla->body->rows[$i]->row[2]->text=htmlspecialchars($row['nick']);
        $ranking->tabla->body->rows[$i]->row[2]->link="userinfo.php?user=".$row['user_id'];
        $ranking->tabla->body->rows[$i]->row[2]->textAlign="center";
        $ranking->tabla->body->rows[$i]->row[3]->text=intval($row['solved']);
        $ranking->tabla->body->rows[$i]->row[3]->link="status.php?user_id="
            .$row['user_id']."&jresult=4";
        $ranking->tabla->body->rows[$i]->row[3]->textAlign="center";
        $ranking->tabla->body->rows[$i]->row[4]->text=intval($row['submit']);
        $ranking->tabla->body->rows[$i]->row[4]->link="status.php?user_id=".$row['user_id'];
        $ranking->tabla->body->rows[$i]->row[4]->textAlign="center";
        
        if ($row['submit'] == 0){
            $ranking->tabla->body->rows[$i]->row[5]->text="0.000%";
        }else{
            $ranking->tabla->body->rows[$i]->row[5]->text=sprintf("%.03lf%%", 100*$row['solved']/$row['submit']);
        }
        $ranking->tabla->body->rows[$i]->row[5]->textAlign="center";
        
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
    $ranking->pageTotal=intval($view_total);
    $ranking->pageSize=intval($page_size);
    $ranking->getScope=isset($_GET['scope'])?$_GET['scope']:""; // getScope
    $ranking->getUserId=getUserId();
    

    header("HTTP/1.1 200 OK");
    echo json_encode($ranking);
    exit();


    header("HTTP/1.1 400 Bad Request");
}

?>
