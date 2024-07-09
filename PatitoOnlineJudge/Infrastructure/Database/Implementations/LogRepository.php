<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILogRepository;
use PDO;

class LogRepository implements ILogRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }
    public function addRecordHistory($hash, $userId, $ip, $ua, $uri, $refer) {
        $now = time();
        $sql = "INSERT INTO online_history(hash,user_id, ip, ua, uri, refer, firsttime, lastmove, timestamp)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, now())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$hash, $userId, $ip, $ua, $uri, $refer, $now, $now]);
    }
}
