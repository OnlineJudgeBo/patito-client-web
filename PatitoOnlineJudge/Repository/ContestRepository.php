<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

class ContestRepository {
    private $pdo;

    public function __construct(DatabaseConnector $connector) {
        $this->pdo = $connector->getConnection();
    }

    public function getContestById($cid) {
        $stmt = $this->pdo->query("SELECT * FROM `contest` WHERE `contest_id` = :cid");
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContests() {
        $stmt = $this->pdo->query("SELECT * FROM `contest` WHERE `defunct` = 'N' ORDER BY `contest_id` DESC LIMIT 50");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByContestId($cid) {
        $stmt = $this->pdo->prepare("
            SELECT cp.problem_id, p.title, p.source 
            FROM `contest_problem` AS cp 
            JOIN `problem` AS p ON cp.problem_id = p.problem_id 
            WHERE cp.contest_id = :cid AND p.defunct = 'N'
            ORDER BY cp.num
        ");
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
