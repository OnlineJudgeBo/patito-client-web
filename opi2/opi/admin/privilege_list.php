<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
echo "<title>Privilege List</title>"; 
echo "<center><h2>Privilege List</h2></center>";
$sql="select * FROM privilege where rightstr in ('administrator','source_browser','contest_creator','problem_master_editor','http_judge','problem_editor') ";
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table class='table table-striped' width=60% border=1>";
echo "<thead><tr><td>user<td>right<td>defunc</tr></thead>";
$rowOr=array();
for (;$row=mysql_fetch_object($result);){
    array_push($rowOr, $row);
}

for ($i=0;$i<count($rowOr);$i++){
    for ($j=0;$j<count($rowOr);$j++){
        if(strtolower($rowOr[$i]->user_id)<strtolower($rowOr[$j]->user_id)){
            $temp = $rowOr[$i];
            $rowOr[$i] = $rowOr[$j];
            $rowOr[$j] = $temp;
        }
    }
}
for ($i=0;$i<count($rowOr);$i++){
	echo "<tr>";
	echo "<td>".$rowOr[$i]->user_id;
	echo "<td>".$rowOr[$i]->rightstr;
//	echo "<td>".$rowOr[$i]->start_time;
//	echo "<td>".$rowOr[$i]->end_time;
//	echo "<td><a href=contest_pr_change.php?cid=$rowOr[$i]->contest_id>".($rowOr[$i]->private=="0"?"Public->Private":"Private->Public")."</a>";
	//if(($rowOr[$i]->user_id=="OscarGauss") || ($rowOr[$i]->user_id=="starsaminf") )
    //    echo "<td>Delete";
    //else
        echo "<td><a href=privilege_delete.php?uid=".$rowOr[$i]->user_id."&rightstr=".$rowOr[$i]->rightstr."&getkey=".$_SESSION['getkey'].">Delete</a>";
//	echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
//	echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
	echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>

