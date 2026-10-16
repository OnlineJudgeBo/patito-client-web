<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PDO;

class SolutionRepository implements ISolutionRepository
{
    private $pdo;
    private $view_last_runs = [];
    private $site_id;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function getLastRuns()
    {
        require __DIR__ . "/../../../../Legacy/Include/const.inc.php";

        $sql = "SELECT solution_id, problem_id, user_id, time, memory, in_date, result, language
                FROM solution
                WHERE problem_id > 0
                and site_id = $this->site_id
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

    // $includeTests false leaves out the failed test of a submission scored by points (IOI style):
    // those tests stay hidden, only the compiler output is shown.
    public function getErrorResult($solution_id, $includeTests = true)
    {
        $hidden = $includeTests ? "" : " AND NOT EXISTS (SELECT 1 FROM solution s WHERE s.solution_id = runtimeinfo.solution_id AND s.pass_rate > 0)
            AND NOT EXISTS (SELECT 1 FROM solution_subtask t WHERE t.solution_id = runtimeinfo.solution_id)";
        $qry = "SELECT error
        FROM (
            SELECT error, solution_id
            FROM compileinfo
            WHERE solution_id = :sid1
            UNION ALL
            SELECT error, solution_id
            FROM runtimeinfo
            WHERE solution_id = :sid2" . $hidden . "
        ) AS combined_errors, solution
        WHERE solution.solution_id = combined_errors.solution_id
        AND solution.site_id = :site_id
        LIMIT 1;";
        $stmt = $this->pdo->prepare($qry);
        $stmt->execute([':sid1' => $solution_id, ':sid2' => $solution_id, ':site_id' => $this->site_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatusData($params, $limit, $site_id)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");

        // has_detail: the judge left something to show for the submission (compile error, failed test).
        $sql = "SELECT solution.*, similar_code.similar_s_id, similar_code.percentage, problem.spj,
            (EXISTS (SELECT 1 FROM runtimeinfo r WHERE r.solution_id = solution.solution_id)
                OR EXISTS (SELECT 1 FROM compileinfo c WHERE c.solution_id = solution.solution_id)) AS has_detail,
            (solution.pass_rate > 0
                OR EXISTS (SELECT 1 FROM solution_subtask t WHERE t.solution_id = solution.solution_id)) AS is_scored
        FROM solution
        INNER JOIN  problem ON problem.problem_id = solution.problem_id
        LEFT JOIN similar_code ON solution.solution_id = similar_code.solution_id
        WHERE solution.problem_id > 0
        AND site_id = :site_id";
        $bindings = [':site_id' => $site_id];

        if (isset($params['contest_id'])) {
            $sql .= " AND `contest_id` = :contest_id";
            $bindings[':contest_id'] = intval($params['contest_id']);
        } else {
            // Not COALESCE(contest_id, 0): wrapping the column hides it from the optimizer.
            $sql .= " AND (contest_id IS NULL OR contest_id = 0)";
        }

        if (isset($params['problem_id'])) {
            $sql .= " AND problem.problem_id = :problem_id";
            $bindings[':problem_id'] = intval($params['problem_id']);
        }

        if (isset($params['user_id'])) {
            $sql .= " AND `user_id` = :user_id";
            $bindings[':user_id'] = $params['user_id'];
        }

        if (isset($params['language'])) {
            $language = intval($params['language']);
            if ($language >= 0 && $language < count($language_ext)) {
                $sql .= " AND `language` = :language";
                $bindings[':language'] = $language;
            }
        }

        if (isset($params['jresult'])) {
            $result = intval($params['jresult']);
            if ($result >= 0 && $result <= 12) {
                $sql .= " AND `result` = :jresult";
                $bindings[':jresult'] = $result;
            }
        }

        // Newest first by the primary key, the order they were sent in: the latest rows are read
        // straight from the index instead of sorting every submission of the site by in_date.
        if (isset($params['contest_id'])) {
            $sql .= " ORDER BY solution.solution_id DESC";
        } elseif ($limit != -1) {
            $sql .= " ORDER BY solution.solution_id DESC LIMIT " . intval($limit);
        } else {
            $sql .= " ORDER BY solution.solution_id DESC LIMIT 200";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummarySolutions($user_id, $site_id)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");
        $sql = "SELECT 
        problem.problem_id,
        problem.title,
        solution.solution_id,
        solution.user_id,
        solution.in_date,
        solution.result
        FROM 
            solution
        INNER JOIN 
            problem ON problem.problem_id = solution.problem_id
        WHERE 
            solution.problem_id > 0
            AND (solution.contest_id IS NULL OR solution.contest_id = 0)
            AND solution.user_id = :user_id
            AND solution.result = '4'
            AND solution.site_id = :site_id
        ORDER BY solution.in_date ASC LIMIT 10000000";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':site_id' => $site_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummaryErrorSolutions($user_id, $site_id)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");
        $sql = "SELECT 
        problem.problem_id,
        problem.title,
        solution.solution_id,
        solution.user_id,
        solution.in_date,
        solution.result
        FROM 
            solution
        INNER JOIN 
            problem ON problem.problem_id = solution.problem_id
        WHERE 
            solution.problem_id > 0
            AND (solution.contest_id IS NULL OR solution.contest_id = 0)
            AND solution.user_id = :user_id
            AND solution.result != 4
            AND solution.site_id = :site_id
        ORDER BY solution.in_date ASC LIMIT 10000000";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':site_id' => $site_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
