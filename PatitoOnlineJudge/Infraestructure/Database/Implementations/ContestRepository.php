<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRepository;
use PDO;

class ContestRepository implements IContestRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getContestById($cid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `contest` WHERE `contest_id` = :cid");
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContests()
    {
        $stmt = $this->pdo->query("SELECT * FROM `contest` WHERE `defunct` = 'N' ORDER BY `contest_id` DESC LIMIT 50");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByContestId($cid)
    {
        $stmt = $this->pdo->prepare("SELECT *
            FROM (
                SELECT problem.title AS title, problem.problem_id AS pid, source AS source, contest_problem.num AS pnum
                FROM contest_problem, problem
                WHERE contest_problem.problem_id=problem.problem_id
                AND problem.defunct='N'
                AND contest_problem.contest_id=:cid1
                ) problem
            LEFT JOIN (
                SELECT problem_id pid1,count(1) accepted
                FROM solution
                WHERE result=4 AND contest_id= :cid2
                GROUP BY pid1) p1 ON problem.pid=p1.pid1
            LEFT JOIN (
                SELECT problem_id pid2,count(1) submit
                FROM solution WHERE contest_id=:cid3
                GROUP BY pid2) p2 ON problem.pid=p2.pid2
            ORDER BY pnum");

        $stmt->execute(['cid1' => $cid, 'cid2' => $cid, 'cid3' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isContestByIdPublic($cid)
    {
        if (($this->getContestById($cid)["private"]) == 0) {
            return true;
        }
        return false;
    }
}
