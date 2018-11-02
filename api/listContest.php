<?php
require_once('../include/db_info.inc.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $list = array();
    $sql="SELECT * FROM contest WHERE defunct='N' ORDER BY contest_id DESC limit 100";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_object($result)){
        $fecha=date("Y-m-d H:i:s");
        //if (!isset($_SESSION['administrator']) && intval($row->private)!=0) continue;
        if (time()>strtotime($row->end_time)) continue;
        $order=array("\r\n", "\n", "\r");
        $titulo=str_replace($order, "\\n", $row->title);
        $titulo=str_replace("\"", "\\\"", $titulo);
        array_push($list, ["id"=>$row->contest_id, "title"=>$titulo, "start"=>$row->start_time, "end"=>$row->end_time, "now"=>$fecha, "tipo"=>$row->private]);
    }
    mysql_free_result($result);
    echo json_encode($list);
}
?>
