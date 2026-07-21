<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IuserStaticRepository;
use PDO;

class UserStaticRepository implements IuserStaticRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getTotalUserSubmitByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserAcByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result = 4 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserPeByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result = 5 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserWaByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result = 6 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserTleByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result=7 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserOleByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result=9 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserReByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result=10 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }

    public function getTotalUserCeByProblem($problem_id, $site_id)
    {
        $sql = "SELECT count(user_id) AS total FROM solution WHERE problem_id=:problem_id AND result = 11 AND site_id = :site_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($result['total']);
    }
}
