<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Database\DatabaseConnector;

class IndexController
{
    private $view_title;
    private $view_news;
    private $view_last_runs;

    public function __construct()
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->loadData();
    }

    private function loadData()
    {
        require __DIR__ . "/../../../Legacy/Include/const.inc.php";
        $connector = new DatabaseConnector();
        $pdo = $connector->getConnection();

        // Consulta de noticias
        $sql = "SELECT * FROM `news` WHERE `defunct`!='Y' ORDER BY `importance` ASC,`time` DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        $this->view_news = $stmt->fetchAll();

        // Consulta de últimas soluciones
        $sql = "SELECT solution_id, problem_id, user_id, time, memory, in_date, result, language FROM solution WHERE problem_id > 0 AND contest_id IS NOT NULL ORDER BY in_date DESC LIMIT 10";
        $stmt = $pdo->query($sql);
        $this->view_last_runs = [];

        while ($row = $stmt->fetch()) {
            $tmp = array();
            $tmp["solution_id"] = $row["solution_id"];
            $tmp["problem_id"] = '<a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">' . $row["problem_id"] . '</a>';
            $tmp["user_id"] = '<a class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" href="problem.php?cid=2790&amp;pid=25">' . $row["user_id"] . '</a>';
            $tmp["time"] = '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$row["result"]] . '">' . $row["time"] . '</div>';
            $tmp["memory"] = '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$row["result"]] . '">' . $row["memory"] . '</div>';
            $tmp["in_date"] = $row["in_date"];
            $tmp["result"] = '<div class="font-bold decoration-solid decoration-sky-500 result-' . $judge_color[$row["result"]] . '">' . $judge_result[$row["result"]] . '</div>';
            $tmp["language"] = $language_name[$row["language"]];
            $this->view_last_runs[] = $tmp;
        }
    }

    public function render()
    {
        $view_title = $this->view_title;
        $view_news = $this->view_news;
        $view_last_runs = $this->view_last_runs;

        require_once __DIR__ . "../../View/template/ZaDuckOJ/index.php";
    }
}

$indexController = new IndexController();
$indexController->render();
