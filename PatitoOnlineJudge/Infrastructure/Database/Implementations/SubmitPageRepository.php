<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Infrastructure\Database\EntityObjects\SolutionModel;
use PDO;

class SubmitPageRepository implements ISubmitPageRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function saveSolutionAndReturnId(SolutionModel $solutionModel, $site_id)
    {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, num, site_id) 
        VALUES (:pid, :user_id, NOW(), :language, :ip, :len, :num, :site_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pid', $solutionModel->problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $solutionModel->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':language', $solutionModel->language, PDO::PARAM_INT);
        $stmt->bindParam(':ip', $solutionModel->ip, PDO::PARAM_STR);
        $stmt->bindParam(':len', $solutionModel->code_length, PDO::PARAM_INT);
        $stmt->bindParam(':num', $solutionModel->num, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function saveContestSolutionAndReturnId(SolutionModel $solutionModel, $site_id)
    {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, contest_id, num, site_id) 
        VALUES (:problem_id, :user_id, NOW(), :language, :ip, :len, :cid, :num, :site_id)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $solutionModel->problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $solutionModel->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':language', $solutionModel->language, PDO::PARAM_INT);
        $stmt->bindParam(':ip', $solutionModel->ip, PDO::PARAM_STR);
        $stmt->bindParam(':len', $solutionModel->code_length, PDO::PARAM_INT);
        $stmt->bindParam(':cid', $solutionModel->contest_id, PDO::PARAM_INT);
        $stmt->bindParam(':num', $solutionModel->num, PDO::PARAM_INT);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function saveVirtualContestSolutionAndReturnId(SolutionModel $solutionModel, $site_id)
    {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, contest_id, num, is_virtual, site_id) 
        VALUES (:problem_id, :user_id, NOW(), :language, :ip, :len, :cid, :num, :is_virtual, :site_id)";

        $is_virtual = true;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':problem_id', $solutionModel->problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $solutionModel->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':language', $solutionModel->language, PDO::PARAM_INT);
        $stmt->bindParam(':ip', $solutionModel->ip, PDO::PARAM_STR);
        $stmt->bindParam(':len', $solutionModel->code_length, PDO::PARAM_INT);
        $stmt->bindParam(':cid', $solutionModel->contest_id, PDO::PARAM_INT);
        $stmt->bindParam(':num', $solutionModel->num, PDO::PARAM_INT);
        $stmt->bindParam(':is_virtual', $is_virtual, PDO::PARAM_BOOL);
        $stmt->bindParam(':site_id', $site_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
}
