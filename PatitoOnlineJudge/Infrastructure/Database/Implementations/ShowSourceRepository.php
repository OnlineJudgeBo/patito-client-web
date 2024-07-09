<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IShowSourceRepository;
use PDO;

class ShowSourceRepository implements IShowSourceRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function showCode($solution_id, $user_id, $isAdmin)
    {
        if ($isAdmin) {
            $sql = "SELECT *
            FROM solution, source_code
            WHERE solution.solution_id = :solution_id
            AND source_code.solution_id = solution.solution_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':solution_id' => $solution_id]);
        } else {
            $sql = "SELECT *
            FROM solution, source_code
            WHERE solution.solution_id = :solution_id
            AND solution.user_id = :user_id
            AND source_code.solution_id = solution.solution_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':solution_id' => $solution_id, ':user_id' => $user_id]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
