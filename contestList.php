 <?php
 $view_title= $MSG_CONTEST;

 function formatTimeLength($length){
 	$hour = 0;
 	$minute = 0;
 	$second = 0;
 	$result = '';
 	if ($length >= 60){
 		$second = $length % 60;
 		if ($second > 0){
 			$result = $second . 's ';
 		}
 		$length = floor($length / 60);
 		if ($length >= 60){
 			$minute = $length % 60;
 			if ($minute == 0){
 				if ($result != ''){
 					$result = '0m ' . $result;
 				}
 			}
 			else{
 				$result = $minute . 'm ' . $result;
 			}
 			$length = floor($length / 60);
 			if ($length >= 24){
 				$hour = $length % 24;
 				if ($hour == 0){
 					if ($result != ''){
 						$result = '0h ' . $result;
 					}
 				}
 				else{
 					$result = $hour . 'h ' . $result;
 				}
 				$length = floor($length / 24);
 				$result = $length . 'd ' . $result;
 			}
 			else{
 				$result = $length . 'h ' . $result;
 			}
 		}
 		else{
 			$result = $length . 'm ' . $result;
 		}
 	}
 	else{
 		$result = $length . 's ';
 	}
 	return $result;
 }



 $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
 $result=mysql_query($sql);

 $view_contest=Array();
 $i=0;
 while ($row=mysql_fetch_object($result)){


 	$start_time = strtotime($row->start_time);
 	$end_time   = strtotime($row->end_time);
 	$now        = time();
 	$length     = $end_time-$start_time;
 	$left       = $end_time-$now;

	// past
 	if ($now>$end_time) {
 		continue;
 	}else{
	// pending
 		//$view_contest[$i][0]    = $row->contest_id;
 		$view_contest[$i][1]    = "<a href='contest.php?cid=$row->contest_id'><h3>$row->title</h3>";
 		
 		//falta
 		if ($now<$start_time){
 			$view_contest[$i][2] = "<span class=blue> $MSG_Start $row->start_time</span>&nbsp<br>";
 			$view_contest[$i][2].= "<span class=green>$MSG_TotalTime ".formatTimeLength($start_time-$now)."</span>";
 		}else{
	// running
 			$view_contest[$i][2] = "<span class=red> $MSG_Running</font>&nbsp;<br>";
 			$view_contest[$i][2].= "<span class=green> $MSG_LeftTime ".formatTimeLength($left)." </span><br>";

 		}
 		$view_contest[$i][2].="</a>";

 		

 	}
 	$i++;
 }

 mysql_free_result($result);


 require("template/".$OJ_TEMPLATE."/contestsetlist.php");
 ?>
