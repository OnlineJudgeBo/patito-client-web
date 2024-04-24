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

    public function isContestActive($cid)
    {
        $currentDate = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("SELECT count(contest_id) AS result
        FROM contest
        WHERE contest_id = :cid
        AND timediff(:start_time1, start_time) >= 0
        AND timediff(end_time, :start_time2) >= 0;");
        $stmt->execute([
            ':cid' => $cid,
            ':start_time1' => $currentDate,
            ':start_time2' => $currentDate
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result["result"]) > 0;
    }

    public function getContestById($cid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contest WHERE contest_id = :cid");
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContests()
    {
        $stmt = $this->pdo->query("SELECT * FROM contest WHERE defunct = 'N' ORDER BY contest_id DESC LIMIT 50");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByContestId($cid)
    {
        $stmt = $this->pdo->prepare("SELECT *
        FROM (
            SELECT
                problem.title AS title,
                problem.problem_id AS pid,
                problem.source AS source,
                contest_problem.num AS pnum
            FROM
                contest_problem, problem
            WHERE
                contest_problem.problem_id = problem.problem_id
                AND contest_problem.contest_id = :cid1
        ) problem
        LEFT JOIN (
            SELECT
                problem_id AS pid1,
                COUNT(DISTINCT user_id) AS accepted
            FROM
                solution
            WHERE
                result = 4
                AND contest_id = :cid2
            GROUP BY
                pid1
        ) p1 ON problem.pid = p1.pid1
        LEFT JOIN (
            SELECT
                problem_id AS pid2,
                COUNT(1) AS submit
            FROM
                solution
            WHERE
                contest_id = :cid3
            GROUP BY
                pid2
        ) p2 ON problem.pid = p2.pid2
        ORDER BY
            pnum");

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

    public function getAcProblemsByIdContest($cid)
    {
        $stmt = $this->pdo->prepare("SELECT user_id, contest_id, num
            FROM solution
            WHERE contest_id =:contest_id
            AND result = 4");
        $stmt->execute([':contest_id' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemTitleByNumber($cid, $num)
    {
        $stmt = $this->pdo->prepare("SELECT problem.title AS title
        FROM contest_problem, problem
        WHERE contest_problem.problem_id = problem.problem_id
        AND contest_problem.contest_id = :cid
        AND contest_problem.num = :num");
        $stmt->execute(['cid' => $cid, 'num' => $num]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["title"];
    }

    public function getProblemIdByNum($cid, $num)
    {
        $stmt = $this->pdo->prepare("SELECT problem.problem_id AS problem_id
        FROM contest_problem, problem
        WHERE contest_problem.problem_id = problem.problem_id
        AND contest_problem.contest_id = :cid
        AND contest_problem.num = :num");
        $stmt->execute(['cid' => $cid, 'num' => $num]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["problem_id"];
    }

    public function getLanguagesAvailable($cid) {
        $stmt = $this->pdo->prepare("SELECT contest_id, language_id
            FROM contest_programming_language
            WHERE contest_id =:contest_id");
        $stmt->execute([':contest_id' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLanguages() {
        $stmt = $this->pdo->prepare("SELECT language_id
            FROM programing_language");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}
