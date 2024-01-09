<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

class ProblemRepository {
    private $pdo;

    public function __construct(DatabaseConnector $connector) {
        $this->pdo = $connector->getConnection();
    }

    public function getProblemById($pid) {
        $stmt = $this->pdo->prepare("SELECT * FROM problem
                                        WHERE problem_id = :pid");
        $stmt->execute(['pid' => $pid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemByContestId($cid, $pid) {
        $stmt = $this->pdo->prepare("SELECT * FROM problem
                                        WHERE defunct = 'N' AND
                                        problem_id = (SELECT problem_id
                                                        FROM contest_problem
                                                        WHERE contest_id = :cid
                                                        AND num = :pid)");
        $stmt->execute(['cid' => $cid, 'pid' => $pid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemsCount() {
        $sql = "SELECT COUNT(problem_id) as total 
                FROM problem 
                WHERE defunct='N'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getProblems($offset, $limit) {
        $sql = "SELECT problem_id, title, source, submit, accepted, tags 
                FROM problem
                WHERE defunct='N'
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
