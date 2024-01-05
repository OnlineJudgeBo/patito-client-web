<?php
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
require_once ("./include/const.inc.php");

$view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";

$view_news = [];
$sql = "SELECT *
FROM `news`
WHERE `defunct`!='Y'
ORDER BY `importance` ASC,`time` DESC
LIMIT 1";
$result = mysql_query($sql);

if (!$result) {
    echo mysql_error();
} else {
    while ($row = mysql_fetch_array($result)) {
        $tmp = array();
        $tmp["title"] = $row["title"];
        $tmp["content"] = $row["content"];
        $view_news[] = $tmp;
    }
    mysql_free_result($result);
}

$sql = "SELECT solution_id, problem_id, user_id, time, memory, in_date, result, language
FROM solution
WHERE problem_id > 0 AND
contest_id IS NOT NULL
ORDER BY in_date 
DESC LIMIT 10;";

$result = mysql_query($sql);
$view_last_runs = [];

if (!$result) {
    echo mysql_error();
} else {
    while ($row = mysql_fetch_array($result)) {
        $tmp = array();
        $tmp["solution_id"] = $row["solution_id"];
        $tmp["problem_id"] = '<a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">'.$row["problem_id"].'</a>';
        $tmp["user_id"] = '<a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">'.$row["user_id"].'</a>';
        $tmp["time"] = '<div class="font-bold  decoration-solid decoration-sky-500 result-'.$judge_color[$row["result"]].'">'.$row["time"].'</div>';
        $tmp["memory"] = '<div class="font-bold  decoration-solid decoration-sky-500 result-'.$judge_color[$row["result"]].'">'.$row["memory"].'</div>';
        $tmp["in_date"] = $row["in_date"];
        $tmp["result"] = '<div class="font-bold  decoration-solid decoration-sky-500 result-'.$judge_color[$row["result"]].'">'.$judge_result[$row["result"]].'</div>';
        $tmp["language"] = $language_name[$row["language"]];
        $view_last_runs[] = $tmp;
    }
    mysql_free_result($result);
}

require("template/" . $OJ_TEMPLATE . "/index.php");
