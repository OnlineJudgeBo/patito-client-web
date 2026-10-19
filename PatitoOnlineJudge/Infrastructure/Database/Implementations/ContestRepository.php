<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

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

    public function isContestActive($cid, $site_id)
    {
        $currentDate = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("SELECT count(*) AS result
        FROM contest, contest_site
        WHERE contest.contest_id = :cid
        AND contest.defunct <> 'D'
        AND (
            (:start_time1 BETWEEN contest.start_time AND contest.end_time)
            OR
            (contest.private = 0 AND :start_time2 > contest.end_time)
            )
        AND contest_site.contest_id = contest.contest_id
        AND contest_site.site_id = :site_id");
/*
        $stmt = $this->pdo->prepare("SELECT count(contest.contest_id) AS result
        FROM contest, contest_site
        WHERE contest.contest_id = :cid
        AND timediff(:start_time1, start_time) >= 0
        AND timediff(end_time, :start_time2) >= 0
        AND contest_site.contest_id = contest.contest_id
        AND contest_site.site_id = :site_id");
*/
        $stmt->execute([
            ':cid' => $cid,
            ':start_time1' => $currentDate,
            ':start_time2' => $currentDate,
            ':site_id'     => $site_id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result["result"]) > 0;
    }

    public function isVirtualContest($cid, $site_id)
    {
        $stmt = $this->pdo->prepare("SELECT count(contest.contest_id) AS result
        FROM contest, contest_site
        WHERE contest.contest_id = :cid
        AND contest.defunct = 'O'
        AND contest_site.contest_id = contest.contest_id
        AND contest_site.site_id = :site_id");
        $stmt->execute([':cid' => $cid, ':site_id' => $site_id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result["result"]) > 0;
    }

    public function isContestAcceptingSubmissions($cid, $site_id)
    {
        $currentDate = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("SELECT count(contest.contest_id) AS result
        FROM contest
        INNER JOIN contest_site ON contest_site.contest_id = contest.contest_id
        WHERE contest.contest_id = :cid
        AND :current_date BETWEEN contest.start_time AND contest.end_time
        AND contest_site.site_id = :site_id");
        $stmt->execute([
            ':cid' => $cid,
            ':current_date' => $currentDate,
            ':site_id' => $site_id
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result["result"]) > 0;
    }

    public function getContestById($cid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contest WHERE contest_id = :cid AND defunct <> 'D'"); // 'D' = deleted from the admin
        $stmt->execute(['cid' => $cid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllContests($site_id, $year = null)
    {
        $sql = "SELECT contest.*
            FROM contest
            INNER JOIN contest_site ON contest_site.contest_id = contest.contest_id
            WHERE contest.defunct = 'N'
            AND contest_site.site_id = :site_id
            AND YEAR(contest.start_time) >= YEAR(CURDATE()) - 1"; // Ultimo años
        $params = [':site_id' => $site_id];

        if ($year !== null) {
            $sql .= " AND YEAR(contest.start_time) = :year";
            $params[':year'] = $year;
        }

        $sql .= " ORDER BY contest.contest_id DESC";
        if ($year === null) {
            $sql .= " LIMIT 50";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Announcements and answers sent to everyone, plus this user's own questions (same rule as the admin API).
    public function getClarifications($cid, $site_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, problem_num, question, answer, is_public, created_at, answered_at
            FROM contest_clarification
            WHERE contest_id = :cid AND site_id = :site_id AND (is_public = 1 OR user_id = :user_id)
            ORDER BY created_at DESC, id DESC");
        $stmt->execute([':cid' => $cid, ':site_id' => $site_id, ':user_id' => $user_id ?? '']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastClarificationQuestionAt($cid, $site_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT MAX(created_at) FROM contest_clarification
            WHERE contest_id = :cid AND site_id = :site_id AND user_id = :user_id");
        $stmt->execute([':cid' => $cid, ':site_id' => $site_id, ':user_id' => $user_id]);
        return $stmt->fetchColumn();
    }

    public function countPendingClarificationQuestions($cid, $site_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM contest_clarification
            WHERE contest_id = :cid AND site_id = :site_id AND user_id = :user_id AND answer IS NULL");
        $stmt->execute([':cid' => $cid, ':site_id' => $site_id, ':user_id' => $user_id]);
        return intval($stmt->fetchColumn());
    }

    public function addClarificationQuestion($cid, $site_id, $user_id, $num, $question)
    {
        $stmt = $this->pdo->prepare("INSERT INTO contest_clarification (contest_id, site_id, user_id, problem_num, question, is_public, created_at)
            VALUES (:cid, :site_id, :user_id, :num, :question, 0, :created_at)");
        $stmt->execute([
            ':cid' => $cid,
            ':site_id' => $site_id,
            ':user_id' => $user_id,
            ':num' => $num,
            ':question' => $question,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // Running first (ending soonest), then upcoming (starting soonest).
    public function getActiveContests($site_id, $limit)
    {
        $stmt = $this->pdo->prepare("SELECT contest.contest_id, contest.title, contest.start_time, contest.end_time, contest.private
            FROM contest
            INNER JOIN contest_site ON contest_site.contest_id = contest.contest_id
            WHERE contest.defunct = 'N'
            AND contest_site.site_id = :site_id
            AND contest.end_time > :now
            ORDER BY contest.start_time > :now2, IF(contest.start_time > :now3, contest.start_time, contest.end_time)
            LIMIT " . intval($limit));
        $now = date('Y-m-d H:i:s');
        $stmt->execute([':site_id' => $site_id, ':now' => $now, ':now2' => $now, ':now3' => $now]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContestYears($site_id)
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT YEAR(contest.start_time) AS contest_year
            FROM contest
            INNER JOIN contest_site ON contest_site.contest_id = contest.contest_id
            WHERE contest.defunct = 'N'
            AND contest_site.site_id = :site_id
            AND contest.start_time IS NOT NULL
            AND YEAR(contest.start_time) >= YEAR(CURDATE()) - 1
            ORDER BY contest_year DESC");  // Ultimo años
        $stmt->execute([':site_id' => $site_id]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public function getOfficialContests($site_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contest, contest_site
            WHERE contest.defunct = 'O'
            AND contest_site.contest_id = contest.contest_id
            AND contest_site.site_id = :site_id
            ORDER BY contest.start_time IS NULL, contest.start_time DESC, contest.contest_id DESC");
        $stmt->execute([':site_id' => $site_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProblemsByContestId($cid, $site_id)
    {
        $stmt = $this->pdo->prepare("SELECT *
        FROM (
            SELECT
                problem.title AS title,
                problem.problem_id AS pid,
                problem.source AS source,
                contest_problem.num AS pnum
            FROM
                contest_problem, problem, contest_site
            WHERE
                contest_problem.problem_id = problem.problem_id
                AND contest_problem.contest_id = :cid1
                AND contest_site.contest_id = contest_problem.contest_id
                AND contest_site.site_id = :site_id
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
                AND site_id = :site_id2
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
                AND site_id = :site_id3
            GROUP BY
                pid2
        ) p2 ON problem.pid = p2.pid2
        ORDER BY
            pnum");

        $stmt->execute(['cid1' => $cid,
            'cid2' => $cid,
            'cid3' => $cid,
            'site_id' => $site_id,
            'site_id2' => $site_id,
            'site_id3' => $site_id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isContestByIdPublic($cid)
    {
        if (($this->getContestById($cid)["private"]) == 0) {
            return true;
        }
        return false;
    }

    public function getAcProblemsByIdContest($cid, $site_id)
    {
        $stmt = $this->pdo->prepare("SELECT solution.user_id, solution.contest_id, solution.num
            FROM solution
            WHERE solution.contest_id =:contest_id
            AND solution.result = 4
            AND solution.site_id = :site_id");
        $stmt->execute([':contest_id' => $cid, ':site_id' => $site_id]);
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
