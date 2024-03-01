<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IStatusRepository;
use PDO;

class StatusRepository implements IStatusRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getStatusData($params, $limit)
    {
        $language_ext = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");

        $sql = "SELECT solution.*
        FROM solution
        INNER JOIN  problem ON problem.problem_id = solution.problem_id
        WHERE solution.problem_id > 0 ";

        if (isset($params['contest_id'])) {
            $contest_id = intval($params['contest_id']);
            $sql .= " AND `contest_id` = " . intval($contest_id);
        } else {
            $sql .= " AND contest_id IS NULL";
        }

        if (isset($params['problem_id'])) {
            $problem_id = intval($params['problem_id']);
            $sql .= " AND `problem_id` = " . intval($problem_id);
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
}
