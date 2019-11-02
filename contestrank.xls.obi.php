<?php
ini_set("display_errors","On");
ob_start();
header ( "content-type:   application/excel" );
header('Content-Disposition: attachment; filename="Contest Rank.xls"');
?>
<meta charset="utf-8">
<?php
        $OJ_CACHE_SHARE=true;
        $cache_time=10;
        require_once('./include/cache_start.php');
	require_once('./include/db_info.inc.php');
        require_once('./include/setlang.php');
        $view_title= $MSG_CONTEST.$MSG_RANKLIST;
        $title="";
        require_once("./include/const.inc.php");
        require_once("./include/my_func.inc.php");
class TM{
        var $solved=0;
        var $time=0;
        var $p_wa_num;
        var $p_ac_sec;
        var $user_id;
        var $nick;
        var $pass_rate;
        var $points;
        function TM(){
                $this->solved=0;
                $this->time=0;
                $this->p_wa_num  = array(0);
                $this->p_ac_sec  = array(0);
                $this->pass_rate = array(0);
                $this->points    = 0;
        }
        function Add($pid,$sec,$res, $pass_rate = 0, $obi = 0){
              //echo "Add $this->user_id - $pid =  $res $pass_rate <br>";
               if($obi != 1){
                 if (isset($this->p_ac_sec[$pid]) && $this->p_ac_sec[$pid] > 0)
                        return;
                }
                if ($res != 4){
                        if(isset($this->p_wa_num[$pid])){
                                $this->p_wa_num[$pid]++;
                        }else{
                                $this->p_wa_num[$pid]=1;
                        }
                }else{

		if($obi ==  1){
                 $pass_rate = (100-$pass_rate*100);
                 if($pass_rate == 1){
                    $pass_rate = 100;
                 }
                 if ($this->p_ac_sec[$pid] == 0){
                    $this->solved++;
                    $this->p_ac_sec[$pid]  = $sec;
                 }
                 if (($pass_rate) > $this->pass_rate[$pid]){
                     $this->p_ac_sec[$pid]  = $sec;
	             $this->pass_rate [$pid] = $pass_rate;
                 }

                }else{
                    $this->p_ac_sec[$pid]  = $sec;
                     $this->solved++;
                }
               $this->points = 0;
                foreach($this->pass_rate as $index => $value){
                 $this->points += $value;
                }

               $this->time = 0;
                foreach($this->p_ac_sec as $index => $value){
                 $this->time += $value;
                }

                }

        }
}

function s_cmp($A,$B){
        if ($A->solved != $B->solved) 
            return $A->solved < $B->solved;
        else
            return $A->time>$B->time;
}

function points_cmp($A,$B){
        if ($A->points != $B->points) 
            return $A->points < $B->points;
        else
            return $A->time > $B->time;
}


// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

$sql="SELECT obi, `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
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
$obi = 0;
if ($rows_cnt>0){
//      $row=mysql_fetch_array($result);

        if($OJ_MEMCACHE)
                $row=$result[0];
        else
                $row=mysql_fetch_array($result);
        $start_time=strtotime($row['start_time']);
        $end_time=strtotime($row['end_time']);
        $title=$row['title'];
        $obi = $row['obi'];
        
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

$row=mysql_fetch_array($result);

$pid_cnt=intval($row['pbc']);
if(!$OJ_MEMCACHE)mysql_free_result($result);

$sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date, solution.pass_rate
                FROM
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;

$user_cnt=0;
$user_name='';
$U=array();
for ($i=0;$i<$rows_cnt;$i++){
        $row=mysql_fetch_array($result);
        $n_user=$row['user_id'];
        if (strcmp($user_name,$n_user)){
                $user_cnt++;
                $U[$user_cnt]=new TM();

                $U[$user_cnt]->user_id=$row['user_id'];
                $U[$user_cnt]->nick=$row['nick'];

                $user_name=$n_user;
        }
        if( time() < $end_time && $lock < strtotime($row['in_date']) ){
                  if($obi == 1){
                     $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0,0,1);
                  }else {
        	     $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0,0,0);
                  }
        }else{
                  if($obi == 1){
                     if($row['pass_rate'] > 0.0){
                       $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,4,$row['pass_rate'],1);
                     }else{
                       $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']),0,1);
                     }
                  }else {
                     $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']),0);
                  }
        }
}
//echo "<pre>"; print_r($U); echo "</pre>"; exit();
if(!$OJ_MEMCACHE) mysql_free_result($result);
if( $obi == 1){
   usort($U,"points_cmp");
}else{
   usort($U,"s_cmp");
}

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

////////////////////////////////////////////////////////////
$rank = 1;
?>
  <table id="rank">
      <thead>
        <tr class="toprow" align="center">
          <td  width="5%">
            Puesto<th width=10%>Usuario</th><th width=10%>Nombre</th> <th width=10%>Departamento</th> <th width=10%>Ciudad</th> <th width=10%>Colegio</th> <th width=5%>Resuelto</th><th width=5%>Penalidad</th>
            <?php
           if($obi == 1){
            echo '<th width=5%>Puntos</th>';
            }
            for ($i=0;$i<$pid_cnt;$i++)
              echo "<td>$PID[$i]</td>";
            echo "</tr></thead>\n<tbody>";

            for ($i=0;$i<$user_cnt;$i++){
             if ($i&1) echo "<tr class=oddrow align=center>\n";
             else echo "<tr class=evenrow align=center>\n";
             echo "<td>";
             $uuid = $U[$i]->user_id;
             $nick = $U[$i]->nick;
             echo $rank++;
            $usolved = $U[$i]->solved;
            if($uuid == $_GET['user_id']) echo "<td bgcolor=#ffff77>";
            else echo"<td>";
            echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
            echo "<td><a href=userinfo.php?user=$uuid>".$U[$i]->nick."</a>";
            $sql2      = "SELECT school, district, departament FROM users WHERE user_id = '$uuid'";
            $res2      = mysql_query($sql2);
            $row_cnt2 = mysql_num_rows($res2);
            $row2     = mysql_fetch_array($res2);
            $school   = "";
            $district = "";
            $dep      = "";
            if($row_cnt2 == 1){
              $school   = $row2['school'];
              $district = $row2['district'];
              $vcyt     = $row2['vcyte'];
              $dep = $row2['departament'];
    	      if($dep == 1){
                 $dep = "La Paz";
              } else  if($dep == 2){
                 $dep = "Cochabamba";
              } else  if($dep == 3){
                 $dep = "Santa Cruz";
              } else  if($dep == 4){
                 $dep = "Beni";
              } else if($dep == 5){
                 $dep = "Chuquisaca";
              } else  if($dep == 6){
                 $dep = "Oruro";
              } else  if($dep == 7){
                 $dep = "Pando";
              } else if($dep == 8){
                 $dep = "Potosi";
              } else  if($dep == 9){
                 $dep = "Tarija";
              }


            }
            echo "<td>".$dep;
  	    echo "<td>".$district;
            echo "<td>".$school;
            echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
            echo "<td>".sec2str($U[$i]->time);
            if($obi == 1){
              echo "<td>".$U[$i]->points;
            }
 
           for ($j=0;$j<$pid_cnt;$j++){
              $bg_color="eeeeee";
              if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
               $aa = 0x33+$U[$i]->p_wa_num[$j]*32;
               $aa = $aa>0xaa?0xaa:$aa;
               $aa = dechex($aa);
               $bg_color = "$aa"."ff"."$aa";
               if($uuid == $first_blood[$j]){
                $bg_color="aaaaff";
              }
            }else if(isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
              $aa = 0xaa-$U[$i]->p_wa_num[$j]*10;
              $aa = $aa > 16 ?$aa:16;
              $aa = dechex($aa);
              $bg_color = "ff$aa$aa";
            }

            echo "<td class=well style='padding:1px;background-color:$bg_color'>";
            if(isset($U[$i])){
             if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0)
              echo sec2str($U[$i]->p_ac_sec[$j]);
             if($obi == 1 ){
	        if($U[$i]->pass_rate[$j] > 0){
                   echo " (".intval($U[$i]->pass_rate[$j])."%)";
                }else{
                  if (isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j] > 0){
                    echo "(-".$U[$i]->p_wa_num[$j].")";
                  }
                }
             }else{
                 if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0){
                  echo "(-".$U[$i]->p_wa_num[$j].")";
                 }
             }
          }
        }
        echo "</tr>\n";
      }
      echo "</tbody></table></div>";

