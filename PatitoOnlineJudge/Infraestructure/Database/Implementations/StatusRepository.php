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

    public function getStatusData($params)
    {
        $language_name    = array("C", "C++", "Pascal", "Java", "Ruby", "Bash", "Python2", "PHP", "Perl", "C#", "Obj-C", "FreeBasic", "Other Language", "", "", "Python3", "C++11", "Python3.7", "Go", "Python3.12");
        $language_ext     = array("c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "", "", "", "py", "cc", "py", "go", "py");
        $language_visible = array(1,  1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1);


        $sql = "SELECT *
        FROM solution
        INNER JOIN  problem ON problem.problem_id = solution.problem_id
        WHERE solution.problem_id > 0 ";

        if (true) {
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

        $sql .= " ORDER BY `solution_id` DESC LIMIT 23";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
