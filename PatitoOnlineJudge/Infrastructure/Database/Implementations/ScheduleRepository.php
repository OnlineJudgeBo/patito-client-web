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
                    s.day_of_week,
                    s.start_time AS start_time,
                    s.end_time AS end_time,
                    subjects.name AS subject,
                    t.name AS teacher_name,
                    COALESCE(GROUP_CONCAT(a.name ORDER BY a.id SEPARATOR ', '), '') AS assistance_name,
                    COALESCE(GROUP_CONCAT(a.schedule ORDER BY a.id SEPARATOR ' | '), '') AS schedule
                FROM
                    schedules s
                JOIN
                    teachers t ON s.teacher_id = t.id
                JOIN
                    subjects ON s.subject_id = subjects.id
                LEFT JOIN
                    assistants a ON s.subject_id = a.subject_id
                GROUP BY
                    s.id,
                    s.day_of_week,
                    s.start_time,
                    s.end_time,
                    subjects.name,
                    t.name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
