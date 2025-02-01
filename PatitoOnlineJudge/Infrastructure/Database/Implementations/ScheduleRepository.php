<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IScheduleRepository;
use PDO;

class ScheduleRepository implements IScheduleRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getScheduleConnection();
    }

    public function getSchedule()
    {
        $sql = "SELECT
                    s.day_of_week AS day_of_week,
                    s.start_time AS start_time,
                    s.end_time AS end_time,
                    s.subject AS subject,
                    t.name AS teacher_name,
                    a.name AS assistance_name,
                    a.schedule AS schedule
                FROM
                    schedules s
                JOIN
                    teachers t ON s.teacher_id = t.id
                LEFT JOIN
                    assistants a ON t.id = a.teacher_id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
