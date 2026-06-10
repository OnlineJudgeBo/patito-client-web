<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PDO;

class ProblemRepository implements IProblemRepository {
    private $pdo;

    public function __construct(DatabaseConnector $connector) {
        $this->pdo = $connector->getConnection();
    }

    public function getProblemById($pid, $site_id) {
        $stmt = $this->pdo->prepare("SELECT problem.* FROM problem, problems_site
                                        WHERE problem.problem_id = :pid
                                        AND problems_site.problem_id = problem.problem_id
                                        AND problems_site.site_id = :site_id
                                        AND problems_site.is_active = 1");
        $stmt->execute(['pid' => $pid,
                        'site_id' => $site_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemByContestId($cid, $pid, $site_id) {
        $stmt = $this->pdo->prepare("SELECT problem.* FROM problem, problems_site
                                        WHERE problem.defunct='N'
                                        AND problem.problem_id = (
                                            SELECT problem_id FROM contest_problem
                                            WHERE contest_id = :cid
                                            AND num = :pid)
                                        AND problems_site.problem_id = problem.problem_id
                                        AND problems_site.site_id = :site_id
                                        AND problems_site.is_active = 1");
        $stmt->execute(['cid' => $cid,
                        'pid' => $pid,
                        'site_id' => $site_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemByOfficialContestId($cid, $pid, $site_id) {
        $stmt = $this->pdo->prepare("SELECT problem.*
                                        FROM problem
                                        INNER JOIN contest_problem
                                            ON contest_problem.problem_id = problem.problem_id
                                        INNER JOIN contest
                                            ON contest.contest_id = contest_problem.contest_id
                                        INNER JOIN contest_site
                                            ON contest_site.contest_id = contest.contest_id
                                        INNER JOIN problems_site
                                            ON problems_site.problem_id = problem.problem_id
                                        WHERE contest.contest_id = :cid
                                        AND contest_problem.num = :pid
                                        AND contest.defunct = 'O'
                                        AND contest_site.site_id = :contest_site_id
                                        AND problems_site.site_id = :problem_site_id
                                        AND problems_site.is_active = 1");
        $stmt->execute([
            'cid' => $cid,
            'pid' => $pid,
            'contest_site_id' => $site_id,
            'problem_site_id' => $site_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProblemsCount($site_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(problem.problem_id) as total
                                        FROM problem, problems_site
                                        WHERE problems_site.problem_id = problem.problem_id
                                        AND problems_site.site_id = :site_id
                                        AND problems_site.is_active = 1
                                        AND problem.defunct='N'");
        $stmt->execute(['site_id' => $site_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getProblems($offset, $limit, $site_id) {
        $sql = "SELECT problem.problem_id, problem.title, source, submit, accepted
        FROM problem, problems_site
        WHERE defunct = 'N'
            AND problem.problem_id NOT IN (
            SELECT contest_problem.problem_id 
            FROM (
              SELECT contest.*  
                FROM contest, contest_site
                WHERE NOW() BETWEEN contest.start_time AND contest.end_time
                AND contest_site.contest_id = contest.contest_id
                AND contest_site.site_id = :site_id1
            ) c 
            INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
            OR problem.problem_id IN (1000)
        )
        
        AND problems_site.problem_id = problem.problem_id
        AND problems_site.site_id = :site_id2
        AND problems_site.is_active = 1
        LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":site_id1", $site_id, PDO::PARAM_INT);
        $stmt->bindParam(":site_id2", $site_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByUser($offset, $limit, $userId, $site_id) {
        $sql = "SELECT
                    problem.problem_id,
                    problem.title,
                    problem.source,
                    problem.submit,
                    problem.accepted,
                    (
                        SELECT COUNT(*) FROM solution 
                            WHERE solution.problem_id = problem.problem_id
                            AND solution.result = 4
                            AND solution.user_id = :userid1
                            AND solution.site_id = :site_id1
                    ) AS ac,
                    (
                        SELECT COUNT(*) FROM solution
                            WHERE solution.problem_id = problem.problem_id
                            AND solution.result != 4
                            AND solution.user_id =:userid2
                            AND solution.site_id = :site_id2
                    ) AS wa
                FROM
                    problem, problems_site
                WHERE
                    problem.defunct = 'N' AND problem.problem_id NOT IN (
                        SELECT contest_problem.problem_id 
                        FROM (
                            SELECT contest.*  
                            FROM contest, contest_site
                            WHERE NOW() BETWEEN contest.start_time AND contest.end_time
                            AND contest_site.contest_id = contest.contest_id
                            AND contest_site.site_id = :site_id3
                        ) c 
                        INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
                        AND problem.problem_id IN (1000)
                        ORDER BY problem.accepted DESC
                    )
                    AND problems_site.problem_id = problem.problem_id
                    AND problems_site.site_id = :site_id4
                    AND problems_site.is_active = 1
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":userid1", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":userid2", $userId, PDO::PARAM_STR);
        $stmt->bindParam(":site_id1", $site_id, PDO::PARAM_INT);
        $stmt->bindParam(":site_id2", $site_id, PDO::PARAM_INT);
        $stmt->bindParam(":site_id3", $site_id, PDO::PARAM_INT);
        $stmt->bindParam(":site_id4", $site_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isProblemInContest($problem_id, $site_id)
    {
        $sql = "SELECT count(*) AS total
        FROM (
            SELECT contest.*
            FROM contest, contest_site
            WHERE NOW() BETWEEN contest.start_time AND contest.end_time
            AND contest.contest_id = contest_site.contest_id
            AND contest_site.site_id = :site_id 
        ) c 
        INNER JOIN contest_problem ON c.contest_id = contest_problem.contest_id
        WHERE problem_id = :problem_id
            AND problem_id NOT IN (1000)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":problem_id", $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(":site_id", $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']) > 0;
    }
}
