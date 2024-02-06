<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Infraestructure\Database\EntityObjects\SolutionModel;
use PDO;

class SubmitPageRepository implements ISubmitPageRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function saveSolutionAndReturnId(SolutionModel $solutionModel)
    {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, num) 
        VALUES (:pid, :user_id, NOW(), :language, :ip, :len, :num)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':pid', $solutionModel->problem_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $solutionModel->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':language', $solutionModel->language, PDO::PARAM_STR);
        $stmt->bindParam(':ip', $solutionModel->ip, PDO::PARAM_STR);
        $stmt->bindParam(':len', $solutionModel->code_length, PDO::PARAM_INT);
        $stmt->bindParam(':num', $solutionModel->num, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function saveContestSolutionAndReturnId(SolutionModel $solutionModel)
    {
        $sql = "INSERT INTO solution (problem_id, user_id, in_date, language, ip, code_length, contest_id, num) 
        VALUES (:user_id, NOW(), :language, :ip, :len, :cid, :pid)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $solutionModel->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':language', $solutionModel->language, PDO::PARAM_STR);
        $stmt->bindParam(':ip', $solutionModel->ip, PDO::PARAM_STR);
        $stmt->bindParam(':len', $solutionModel->code_length, PDO::PARAM_INT);
        $stmt->bindParam(':cid', $solutionModel->contest_id, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $solutionModel->problem_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }
}
