<?php
namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;

class SolutionRepository
{
    private $view_last_runs = [];

    public function getLastRuns()
    {
        require __DIR__ . "/../../Legacy/Include/const.inc.php";

        $connector = new DatabaseConnector();
        $pdo = $connector->getConnection();

        $sql = "SELECT solution_id, problem_id, user_id, time, memory, in_date, result, language
                FROM solution
                WHERE problem_id > 0
                AND contest_id IS NOT NULL
                ORDER BY in_date DESC LIMIT 10";
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
        return $this->view_last_runs;
    }
}
