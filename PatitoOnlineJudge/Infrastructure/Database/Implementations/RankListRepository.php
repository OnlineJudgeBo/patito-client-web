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

        // Counted per user first, so the site_user index answers it without a temporary table;
        // the nick is joined to the users that made it.
        $sql = "SELECT
                    ranked.user_id,
                    COALESCE(NULLIF(user_profiles.nick, ''), ranked.user_id) AS nick,
                    ranked.solved,
                    ranked.submit
                FROM (
                    SELECT
                        user_id,
                        COUNT(DISTINCT CASE WHEN result = 4 THEN problem_id END) AS solved,
                        COUNT(*) AS submit
                    FROM solution
                    WHERE in_date >= :start_date
                        AND site_id = :site_id
                    GROUP BY user_id
                    HAVING solved > 0
                ) ranked
                LEFT JOIN user_profiles
                    ON user_profiles.user_id = ranked.user_id
                    AND user_profiles.site_id = :profile_site_id
                ORDER BY ranked.solved DESC, ranked.submit ASC, ranked.user_id ASC
                LIMIT :offset, :page_size";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":start_date", $startDate->format("Y-m-d 00:00:00"));
        $stmt->bindValue(":site_id", (int) $site_id, PDO::PARAM_INT);
        $stmt->bindValue(":profile_site_id", (int) $site_id, PDO::PARAM_INT);
        $stmt->bindValue(":offset", max(0, (int) $rank), PDO::PARAM_INT);
        $stmt->bindValue(":page_size", $page_size, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
