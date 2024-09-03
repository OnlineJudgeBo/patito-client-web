<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IRankListRepository;
use PDO;

class RankListRepository implements IRankListRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getRankListByDate($scope, $rank, $site_id)
    {
        $page_size = 500000;
        $s = "";
        if ($scope == "d") {
            $s = date('Y') . '-' . date('m') . '-' . date('d');
        } elseif ($scope == "w") {
            $monday = mktime(0, 0, 0, date("m"), date("d") - (date("w") + 7) % 8 + 1, date("Y"));
            $s = strftime("%Y-%m-%d", $monday);
        } elseif ($scope == "m") {
            $s = date('Y') . '-' . date('m') . '-01';
        } else {
            $s = '2013-01-01';
        }
        $sql = "SELECT user_profiles.user_id, nick, s.solved, t.submit
                FROM user_profiles
                RIGHT JOIN (
                    SELECT count(DISTINCT problem_id) solved ,user_id
                    FROM solution WHERE in_date > str_to_date('$s','%Y-%m-%d')
                    AND result = 4
                    AND site_id = $site_id
                    GROUP BY user_id
                    ORDER BY solved DESC LIMIT " . strval($rank) . ",$page_size
                ) s ON user_profiles.user_id=s.user_id
                LEFT JOIN (
                    SELECT count( problem_id) submit ,user_id
                    from solution
                    where in_date > str_to_date('$s','%Y-%m-%d')
                    group by user_id order by submit desc limit " . strval($rank) . "," . ($page_size * 2) . ") t
                    ON user_profiles.user_id=t.user_id
                ORDER BY s.solved DESC, t.submit  LIMIT  0,50000";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
