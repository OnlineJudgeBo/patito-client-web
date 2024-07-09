<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PDO;

class SolutionRepository implements ISolutionRepository
{
    private $pdo;
    private $view_last_runs = [];

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getLastRuns()
    {
        require __DIR__ . "/../../../../Legacy/Include/const.inc.php";

        $sql = "SELECT solution_id, problem_id, user_id, time, memory, in_date, result, language
                FROM solution
                WHERE problem_id > 0
                AND contest_id IS NOT NULL
                ORDER BY in_date DESC LIMIT 10";
        $stmt = $this->pdo->query($sql);
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

    public function getErrorResult($solution_id)
    {
        $qry = "SELECT error
        FROM (
            SELECT error
            FROM compileinfo
            WHERE solution_id = :sid1
            UNION ALL
            SELECT error
            FROM runtimeinfo
            WHERE solution_id = :sid2
        ) AS combined_errors
        LIMIT 1;";
        $stmt = $this->pdo->prepare($qry);
        $stmt->execute([':sid1' => $solution_id, ':sid2' => $solution_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatusData($params, $limit)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");

        $sql = "SELECT solution.*, similar_code.similar_s_id, similar_code.percentage
        FROM solution
        INNER JOIN  problem ON problem.problem_id = solution.problem_id
        LEFT JOIN similar_code ON solution.solution_id = similar_code.solution_id
        WHERE solution.problem_id > 0 ";

        if (isset($params['contest_id'])) {
            $contest_id = intval($params['contest_id']);
            $sql .= " AND `contest_id` = " . intval($contest_id);
        } else {
            $sql .= " AND COALESCE(contest_id, 0) = 0";
        }

        if (isset($params['problem_id'])) {
            $problem_id = intval($params['problem_id']);
            $sql .= " AND problem.problem_id = " . intval($problem_id);
        }

        if (isset($params['user_id'])) {
            $user_id = $params['user_id'];
            $sql .= " AND `user_id` = '$user_id' ";
        }

        if (isset($params['language'])) {
            $language = intval($params['language']);
            if ($language >= 0 && $language < count($language_ext)) {
                $sql .= " AND `language` = '$language' ";
            }
        }

        if (isset($params['jresult'])) {
            $result = intval($params['jresult']);
            if ($result >= 0 && $result <= 12) {
                $sql .= " AND `result` = '$result' ";
            }
        }

        if (isset($params['contest_id'])) {
            $sql .= " ORDER BY solution.in_date DESC";
        } elseif ($limit != -1) {
            $sql .= " ORDER BY solution.in_date DESC LIMIT " . $limit;
        } else {
            $sql .= " ORDER BY solution.in_date DESC LIMIT 200";
        }
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummarySolutions($user_id)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");
        $sql = "SELECT 
        problem.problem_id,
        problem.title,
        MIN(solution.in_date) AS first_solved_date,
        solution.solution_id,
        COUNT(solution.solution_id) AS submission_count,
        solution.user_id
        FROM 
            solution
        INNER JOIN 
            problem ON problem.problem_id = solution.problem_id
        WHERE 
            solution.problem_id > 0 
            AND solution.contest_id IS NULL 
            AND solution.user_id = '$user_id' 
            AND solution.result = '4'
        GROUP BY 
            problem.problem_id, problem.title 
        ORDER BY `first_solved_date` ASC LIMIT 10000000";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
