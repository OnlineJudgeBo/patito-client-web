<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRankRepository;
use PDO;

class ContestRankRepository implements IContestRankRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getContestDetails($cid)
    {
        $sql = "SELECT obi, `start_time`, `title`, `end_time` FROM `contest` WHERE `contest_id` = :cid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemCount($cid)
    {
        $sql = "SELECT COUNT(1) as pbc FROM `contest_problem` WHERE `contest_id` = :cid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContestSolutions($cid)
    {
        $sql = "SELECT user_profiles.user_id, user_profiles.nick, user_profiles.lastname, solution.result, solution.num, solution.in_date, solution.pass_rate,
                solution.is_virtual
                FROM (SELECT * FROM solution WHERE solution.contest_id = :cid AND num >= 0) solution
                LEFT JOIN user_profiles ON user_profiles.user_id = solution.user_id
                ORDER BY user_profiles.user_id, in_date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cid' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstBlood($cid)
    {
        $pid_cnt = $this->getProblemCount($cid);
        $firstBlood = [];
        for ($i = 0; $i < $pid_cnt["pbc"]; $i++) {
            $sql = "SELECT user_id FROM solution WHERE contest_id = :cid AND result = 4 AND num = :num ORDER BY in_date LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['cid' => $cid, 'num' => $i]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $firstBlood[$i] = $row ? $row['user_id'] : "";
        }
        return $firstBlood;
    }
}
