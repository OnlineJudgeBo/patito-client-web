<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemStatusRepository;
use PDO;

class ProblemStatusRepository implements IProblemStatusRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }


    public function getTopUsersByProblem($problem_id, $site_id)
    {
        $sql = "SELECT
            c.user_id,
            c.att AS attempts,
            c.min_score,
            b.solution_id,
            b.language,
            b.in_date,
            (b.score - 10000000000000000000) / 100000000000 AS s_time,
            ((b.score - 10000000000000000000) % 100000000000) / 100000 AS s_memory,
            ((b.score - 10000000000000000000) % 100000) AS s_cl
            FROM (
                SELECT
                    user_id,
                    COUNT(*) AS att,
                    MIN(10000000000000000000 + time * 100000000000 + memory * 100000 + code_length) AS min_score
                FROM solution
                WHERE problem_id = :problem_id_attempts
                AND result = 4
                AND site_id = :site_id_attempts
                GROUP BY user_id
            ) AS c
            LEFT JOIN (
                SELECT 
                    solution_id,
                    user_id,
                    language,
                    10000000000000000000 + time * 100000000000 + memory * 100000 + code_length AS score,
                    in_date
                FROM solution
                WHERE problem_id = :problem_id_solution
                AND result = 4
                AND site_id = :site_id_solution
            ) AS b ON c.user_id = b.user_id AND c.min_score = b.score
            ORDER BY c.min_score, b.in_date
            LIMIT 100;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'problem_id_attempts' => $problem_id,
            'site_id_attempts' => $site_id,
            'problem_id_solution' => $problem_id,
            'site_id_solution' => $site_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
