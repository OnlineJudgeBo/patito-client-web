<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv='refresh' content='60'>
  <title><?php echo $view_title?></title>
  <link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

</head>
<div id="wrapper">
  <?php require_once("oj-header.php");?>
  <section id="main" >
    <div id="center">

      <form id="simform" action="status.php" method="get">
        <?php echo $MSG_PROBLEM_ID?>:<input style="height:24px" type="text" size="4" name="problem_id" value='<?php echo $problem_id?>'>
        <?php echo $MSG_USER?>:<input style="height:24px" type=text size=4 name=user_id value='<?php echo $user_id?>'>
        <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
        <?php echo $MSG_LANG?>:<select class="input-small" size="1" name="language">
        <?php if (isset($_GET['language'])) $language=$_GET['language'];
        else $language=-1;
        if ($language<0||$language>=count($language_name)) $language=-1;
        if ($language==-1) echo "<option value='-1' selected>All</option>";
        else echo "<option value='-1'>All</option>";
        $i=0;
       
        foreach ($language_name as $lang){
          if ($i==$language)
            echo "<option value=$i selected>$language_name[$i]</option>";
          else if($language_visible[$i] == 1)
            echo "<option value=$i>$language_name[$i]</option>";
          $i++;
        }
        ?>
      </select>
      <?php echo $MSG_RESULT?>:<select size="1" name="jresult">
      <?php if (isset($_GET['jresult'])) $jresult_get=intval($_GET['jresult']);
      else $jresult_get=-1;
      if ($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
      if ($jresult_get==-1) echo "<option value='-1' selected>All</option>";
      else echo "<option value='-1'>All</option>";
      for ($j=0;$j<12;$j++){
        $i=($j+4)%12;
        if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
        else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>";
      }
      echo "</select>";
      ?>

      <?php if(isset($_SESSION['administrator'])||isset($_SESSION['source_browser'])){
        if(isset($_GET['showsim']))
          $showsim=intval($_GET['showsim']);
        else
          $showsim=0;
        echo "SIM:
        <select id=\"appendedInputButton\"  class=\"input-mini\" name=showsim onchange=\"document.getElementById('simform').submit();\">
          <option value=0 ".($showsim==0?'selected':'').">All</option>
          <option value=50 ".($showsim==50?'selected':'').">50</option>
          <option value=60 ".($showsim==60?'selected':'').">60</option>
          <option value=70 ".($showsim==70?'selected':'').">70</option>
          <option value=80 ".($showsim==80?'selected':'').">80</option>
          <option value=90 ".($showsim==90?'selected':'').">90</option>
          <option value=100 ".($showsim==100?'selected':'').">100</option>
        </select>";
      }
      echo "<input type=submit class='input'  value='$MSG_SEARCH'></form>";
      ?>
    </div>

    <div id="center">
      <table id="result-tab" class="table table-striped content-box-header" align="center">
        <thead>
          <tr  class='success toprow' >
            <th ><?php echo $MSG_RUNID?></th>
            <th ><?php echo $MSG_USER?></th>
            <th ><?php echo $MSG_PROBLEM?></th>
            <th ><?php echo $MSG_RESULT?></th>
            <th ><?php echo $MSG_MEMORY?></th>
            <th ><?php echo $MSG_TIME?></th>
            <th ><?php echo $MSG_LANG?></th>
            <th ><?php echo $MSG_CODE_LENGTH?></th>
            <th ><?php echo $MSG_SUBMIT_TIME?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $cnt=0;
          foreach($view_status as $row){
            if ($cnt)
              echo "<tr class='oddrow'>";
            else
              echo "<tr class='evenrow'>";
            foreach($row as $table_cell){
              echo "<td>";
              echo "\t".$table_cell;
              echo "</td>";
            }

            echo "</tr>";

            $cnt=1-$cnt;
          }
          ?>
        </tbody>
      </table>

    </div>
  </section>
</div>
<div id="center">
  <?php echo "[<a href=status.php?".$str2.">Principio</a>]&nbsp;&nbsp;";
  if (isset($_GET['prevtop']))
    echo "[<a href=status.php?".$str2."&top=".$_GET['prevtop'].">Anterior</a>]&nbsp;&nbsp;";
  else
    echo "[<a href=status.php?".$str2."&top=".($top+20).">Anterior</a>]&nbsp;&nbsp;";
  echo "[<a href=status.php?".$str2."&top=".$bottom."&prevtop=$top>Siguiente</a>]";
  ?>
</div>


<section id="foot">
  <?php require_once("oj-footer.php");?>
</section>

</body>
<script type="text/javascript">
  var i=0;
  var judge_result=[<?php
  foreach($judge_result as $result){
    echo "'$result',";
  }
  ?>''];
//alert(judge_result[0]);
function auto_refresh(){
  var tb=window.document.getElementById('result-tab');
                // alert(tb);
                var rows=tb.rows;
                for(var  i=1;i<rows.length;i++){
                  var cell=rows[i].cells[3].innerHTML;
                  var sid=rows[i].cells[0].innerHTML;
                  if(cell.indexOf(judge_result[0])!=-1||cell.indexOf(judge_result[2])!=-1||cell.indexOf(judge_result[3])!=-1){
        //alert(sid);
        fresh_result(sid);
      }
    }
  }
  function findRow(solution_id){
    var tb=window.document.getElementById('result-tab');
    var rows=tb.rows;

    for(var i=1;i<rows.length;i++){
      var cell=rows[i].cells[0];
//              alert(cell.innerHTML+solution_id);
if(cell.innerHTML==solution_id) return rows[i];
}
}

function fresh_result(solution_id)
{
  var xmlhttp;
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     var tb=window.document.getElementById('result-tab');
     var row=findRow(solution_id);
     //alert(row);
     var r=xmlhttp.responseText;
     var ra=r.split(",");
//     alert(r);
//     alert(judge_result[r]);
var loader="<img width=18 src=image/loader.gif>";
row.cells[3].innerHTML="<span class='btn btn-warning'>"+judge_result[ra[0]]+"</span>"+loader;
row.cells[4].innerHTML=ra[1];
row.cells[5].innerHTML=ra[2];
if(ra[0]<4)
  window.setTimeout("fresh_result("+solution_id+")",2000);
else
  window.location.reload();

}
}
xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
xmlhttp.send();
}
//<?php if ($last>0&&$_SESSION['user_id']==$_GET['user_id']) echo "fresh_result($last);";?>

auto_refresh();
</script>

</html>

