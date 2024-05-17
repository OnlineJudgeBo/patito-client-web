<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PDO;

class ProblemRepository implements IProblemRepository {
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
        $sql = "SELECT problem_id, title, source, submit, accepted
        FROM problem
        WHERE defunct = 'N'
            AND problem.problem_id NOT IN (
            SELECT contest_problem.problem_id 
            FROM (
              SELECT * 
                FROM contest
                WHERE NOW() BETWEEN contest.start_time AND contest.end_time
            ) c 
            INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
        )
        LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByUser($offset, $limit, $userId) {
        $sql = "SELECT
                    problem.problem_id,
                    problem.title,
                    problem.source,
                    problem.submit,
                    problem.accepted,
                    (
                        SELECT COUNT(*) FROM solution WHERE solution.problem_id = problem.problem_id AND solution.result = 4 AND solution.user_id = :userid1
                    ) AS ac,
                    (
                        SELECT COUNT(*) FROM solution WHERE solution.problem_id = problem.problem_id AND solution.result != 4 AND solution.user_id =:userid2
                    ) AS wa
                FROM
                    problem
                WHERE
                    problem.defunct = 'N' AND problem.problem_id NOT IN (
                        SELECT contest_problem.problem_id 
                        FROM (
                          SELECT * 
                            FROM contest
                            WHERE NOW() BETWEEN contest.start_time AND contest.end_time
                        ) c 
                        INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
                        ORDER BY problem.accepted DESC
                    )
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":userid1", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":userid2", $userId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isProblemInContest($problem_id)
    {
        $sql = "SELECT count(*) AS total
        FROM (
            SELECT * 
            FROM contest
            WHERE NOW() BETWEEN contest.start_time AND contest.end_time
        ) c 
        INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
        WHERE problem_id = :problem_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":problem_id", $problem_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']) > 0;
    }
}
