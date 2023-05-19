<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
echo "<title>Privilege List</title>"; 
echo "<center><h2>Privilege List</h2></center>";
$sql="select * FROM privilege where rightstr in ('administrator','source_browser','contest_creator','problem_master_editor','http_judge','problem_editor') ORDER BY user_id";
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table class='table table-striped' width=60% border=1>";
echo "<thead><tr><td>user<td>right<td>defunc</tr></thead>";
$rowOr=array();
for (;$row=mysql_fetch_object($result);){
    array_push($rowOr, $row);
}

for ($i=0;$i<count($rowOr);$i++){
	echo "<tr>";
	echo "<td>".$rowOr[$i]->user_id;
	echo "<td>".$rowOr[$i]->rightstr;
        echo "<td><a href='privilege_delete.php?uid=".$rowOr[$i]->user_id."&rightstr=".$rowOr[$i]->rightstr."&getkey=".$_SESSION['getkey']."'>Delete</a>";
	echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>

