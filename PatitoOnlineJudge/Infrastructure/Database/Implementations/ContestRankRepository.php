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

    public function getProblemCount($cid, $site_id)
    {
        $sql = "SELECT COUNT(1) as pbc FROM contest_problem, contest_site 
                    WHERE contest_problem.contest_id = :cid
                    AND contest_problem.contest_id = contest_site.contest_id
                    AND contest_site.site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cid' => $cid, 'site_id' => $site_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getContestSolutions($cid, $site_id)
    {
        $sql = "SELECT user_profiles.user_id, user_profiles.nick, user_profiles.lastname, solution.result, solution.num, solution.in_date, solution.pass_rate,
                solution.is_virtual
                FROM (SELECT * FROM solution WHERE solution.contest_id = :cid AND num >= 0) solution
                LEFT JOIN user_profiles ON user_profiles.user_id = solution.user_id
                WHERE solution.site_id = $site_id
                ORDER BY user_profiles.user_id, in_date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cid' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstBlood($cid, $site_id)
    {
        $pid_cnt = $this->getProblemCount($cid, $site_id);
        $firstBlood = [];
        for ($i = 0; $i < $pid_cnt["pbc"]; $i++) {
            $sql = "SELECT user_id FROM solution 
                        WHERE contest_id = :cid AND result = 4 AND num = :num
                        AND site_id = :site_id
                        ORDER BY in_date LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['cid' => $cid, 'num' => $i, ':site_id' => $site_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $firstBlood[$i] = $row ? $row['user_id'] : "";
        }
        return $firstBlood;
    }
}
