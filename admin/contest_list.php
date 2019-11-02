<?php require("admin-header.php");

        if(isset($OJ_LANG)){
                require_once("../lang/$OJ_LANG.php");
        }


echo "<title>Problem List</title>";
echo "<center><h2>Contest List</h2></center>";
require_once("../include/set_get_key.php");
$sql="SELECT max(`contest_id`) as upid, min(`contest_id`) as btid  FROM `contest`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$base=intval($row->btid);
$cnt=intval($row->upid)-$base;
$cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);
if (isset($_GET['page'])){
        $page=intval($_GET['page']);
}else $page=$cnt;
$pstart=$base+$page_cnt*intval($page-1);
$pend=$pstart+$page_cnt;
for ($i=1;$i<=$cnt;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<span class=red>$i</span>";
        else echo "<a href='contest_list.php?page=".$i."'>".$i."</a>";
}
$sql=sprintf("select contest.contest_id,contest.title,contest.start_time,contest.end_time,contest.private,contest.defunct, privilege.user_id
FROM contest
INNER JOIN privilege ON privilege.rightstr = CONCAT('%s',contest.contest_id) 
where contest.contest_id>=%s and contest.contest_id <=%d 
ORDER BY contest.contest_id DESC","m",$pstart,$pend);
$keyword=$_GET['keyword'];
$keyword=mysql_real_escape_string($keyword);
if($keyword){
$sql=sprintf("select contest.contest_id,contest.title,contest.start_time,contest.end_time,contest.private,contest.defunct, privilege.user_id
        FROM contest
        INNER JOIN privilege ON privilege.rightstr = CONCAT('%s',contest.contest_id) 
        where contest.title like '%s'","m",$pstart,$pend,"%".$keyword."%");
} 
$result=mysql_query($sql) or die(mysql_error());
?>
<form action=contest_list.php class=center><input name=keyword><input type=submit value="<?php echo $MSG_SEARCH?>" ></form>


<?php
echo "<center><table class='table table-striped' width=90% border=1>";
echo "<tr><td>ContestID<td>Title<td>StartTime<td>EndTime<td>Create By</td><td>Private<td>Status<td>Edit<td>Copy<td>Export<td>Logs";
echo "</tr>";
for (;$row=mysql_fetch_object($result);){
        echo "<tr>";
        echo "<td>".$row->contest_id;
        echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a>";
        echo "<td>".$row->start_time;
        echo "<td>".$row->end_time;
        echo "<td>".$row->user_id;
        $cid=$row->contest_id;
        if(isset($_SESSION['administrator'])||isset($_SESSION["m$cid"])){
                echo "<td><a href=contest_pr_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->private=="0"?"Public->Private":"Private->Public")."</a>";
                echo "<td><a href=contest_df_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span class=green>Available</span>":"<span class=red>Reserved</span>")."</a>";
                echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
                echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
                if(isset($_SESSION['administrator'])){
                        echo "<td><a href=\"problem_export_xml.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Export</a>";
                }else{
                  echo "<td>";
                }
     echo "<td> <a href=\"../export_contest_code.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">Logs</a>";
        }else{
                echo "<td colspan=5 align=right><a href=contest_add.php?cid=$row->contest_id>Copy</a><td>";

        }

        echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>

