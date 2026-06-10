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
        $startDate = match ($scope) {
            "d" => new \DateTimeImmutable("today"),
            "w" => new \DateTimeImmutable("today -6 days"),
            "m" => new \DateTimeImmutable("first day of this month"),
            default => new \DateTimeImmutable("1970-01-01"),
        };

        $sql = "SELECT
                    solution.user_id,
                    COALESCE(NULLIF(user_profiles.nick, ''), solution.user_id) AS nick,
                    COUNT(DISTINCT CASE WHEN solution.result = 4 THEN solution.problem_id END) AS solved,
                    COUNT(*) AS submit
                FROM solution
                LEFT JOIN user_profiles
                    ON user_profiles.user_id = solution.user_id
                    AND user_profiles.site_id = solution.site_id
                WHERE solution.in_date >= :start_date
                    AND solution.site_id = :site_id
                GROUP BY solution.user_id, user_profiles.nick
                HAVING solved > 0
                ORDER BY solved DESC, submit ASC, solution.user_id ASC
                LIMIT :offset, :page_size";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":start_date", $startDate->format("Y-m-d 00:00:00"));
        $stmt->bindValue(":site_id", (int) $site_id, PDO::PARAM_INT);
        $stmt->bindValue(":offset", max(0, (int) $rank), PDO::PARAM_INT);
        $stmt->bindValue(":page_size", $page_size, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
