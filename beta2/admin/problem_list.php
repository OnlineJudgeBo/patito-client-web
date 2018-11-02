<?php require("admin-header.php");

if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
}
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator'])
    ||isset($_SESSION['contest_creator'])
    ||isset($_SESSION['problem_editor'])
    ||isset($_SESSION['problem_master_editor'])
    )){
    echo "<a href='../loginpage.php'>No esta autentificado!</a>";
exit(1);
}
$keyword=$_GET['keyword'];
$keyword=mysql_real_escape_string($keyword);
$sql = "SELECT COUNT(DISTINCT contest_id) as total FROM contest";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
$total_contest =  $row->total;

$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-1000;
$cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);




if (isset($_GET['page'])){
    $page=intval($_GET['page']);
}else $page=$cnt;
$pstart=1000+$page_cnt*intval($page-1);
$pend=$pstart+$page_cnt;

echo "<title>Problem List</title>";
echo "<center><h2>Problem List</h2></center>";

for ($i=1;$i<=$cnt;$i++){
    if ($i>1) echo '&nbsp;';
    if ($i==$page) echo "<span class=red>$i</span>";
    else echo "<a href='problem_list.php?page=".$i."'>".$i."</a>";
}

$sql="select `problem_id`,`title`,`in_date`,`defunct`,`tags`,`tags2` FROM `problem` where problem_id>=$pstart and problem_id<=$pend order by `problem_id` desc";
//echo $sql;
if($keyword) $sql="select `problem_id`,`title`,`in_date`,`defunct` FROM `problem` where title like '%$keyword%' or source like '%$keyword%'";
$result=mysql_query($sql) or die(mysql_error());
?>
<form action=problem_list.php><input name=keyword><input type=submit value="<?php echo $MSG_SEARCH?>" ></form>
<?php
echo "<center><table width=90% border=1>";
echo "<form method=post action=contest_add.php>";
echo "<tr><td colspan=13><input type=submit name='problem2contest' value='Check para crear un concurso'>";
echo "<tr><td>PID<td>Nombre<td>Fecha Creacion";
if(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])|| isset($_SESSION['problem_master_editor'])){
    if(isset($_SESSION['administrator'])|| isset($_SESSION['problem_master_editor']))echo "<td>Estado<td>Borrar";
    echo "<td>Edit<td>Datos";
}
echo "<td>Agregado Por</td>";
echo "<td>Uso Concursos</td>";
echo "<td>Lista de uso </td>";
echo "<td>Fecha ultimo uso</td>";
echo "<td>Clasificacion</td>";
echo "<td id='subt'>Sub Clasificacion</td>";
echo "</tr>";
for (;$row=mysql_fetch_object($result);){
    echo "<tr>";
    echo "<td>".$row->problem_id;
    echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
    echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a>";
    echo "<td>".$row->in_date;
    if(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])|| isset($_SESSION['problem_master_editor'])){
        if(isset($_SESSION['administrator']) || isset($_SESSION['problem_master_editor'])){
            echo "<td><a href=problem_df_change.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">"
            .($row->defunct=="N"?"<span titlc='click to reserve it' class=green>Available</span>":"<span class=red title='click to be available'>Reserved</span>")."</a><td>";
            if($OJ_SAE||function_exists("system")){
              ?>
              <a href=# onclick='javascript:if(confirm("Delete?")) location.href="problem_del.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>";'>
                  Delete</a>
                  <?php
              }
          }
          if(isset($_SESSION['administrator'])||isset($_SESSION["p".$row->problem_id])|| isset($_SESSION['problem_master_editor'])){
            echo "<td><a href=problem_edit.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">Edit</a>";
            echo "<td><a href=quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes>TestData</a>";
        }
    }
    $qry = 'SELECT * FROM privilege where rightstr = '.'"p'.$row->problem_id.'"'.'ORDER BY privilege_id';
    $resp=mysql_query($qry);
    echo mysql_error();
    $row2=mysql_fetch_object($resp);
    echo "<td>$row2->user_id </td>";

    $qry = "SELECT count(*) as parcial FROM contest_problem where problem_id = ".$row->problem_id." and contest_id != 0";
    $resp=mysql_query($qry);
    $row2=mysql_fetch_object($resp);
    echo "<td>".$row2->parcial."</td>";
   
    $qry = "SELECT problem_id, contest_id, num FROM contest_problem where problem_id = ".$row->problem_id." and contest_id != 0";
    $resp=mysql_query($qry);
    $tmp = "";
    while($row2=mysql_fetch_object($resp)){
        $tmp .= "<a href=../contest.php?cid=".$row2->contest_id." target=_blank>Prob ".(chr($row2->num+65))."</a><br>"; 
    }
    echo "<td>".$tmp."</td>";
    $tmp = "";

    $qry = "SELECT * FROM contest_problem, contest where  contest_problem.contest_id = contest.contest_id and contest_problem.problem_id =".$row->problem_id;
    $qry.= " and contest_problem.contest_id != 0 order by contest.end_time desc ";
    $resp=mysql_query($qry);
    $row2=mysql_fetch_object($resp);

    echo "<td>".substr($row2->end_time,0,10)."</td>";
     $ta=$row->tags;
    echo "<td>";
    if(strlen($ta)==1)
        echo "Sin clasificar";
    if($ta=="pd")
        echo "Dinamica";
    if($ta=="ma")
        echo "Matematica";
    if($ta=="ad")
        echo"Ad-hoc";
    if($ta=="gr")
        echo"Grafos";
    if($ta=="st")
        echo "String";
    if($ta=="es")
        echo "Estrucutra de Datos";

    echo "</td>";
    echo "<td>".$row->tags2."</td>";

    echo "</tr>";
}
echo "<tr><td colspan=13><input type=submit name='problem2contest' value='Check para crear un concurso'>";

echo "</tr></form>";
echo "</table></center>";
require("../oj-footer.php");
?>
