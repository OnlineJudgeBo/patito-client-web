<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISourceCodeRepository;
use PDO;

class SourceCodeRepository implements ISourceCodeRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function save($solution_id, $source)
    {
        $sql = "INSERT INTO source_code (solution_id, source)
        VALUES (:solution_id, :source)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':solution_id', $solution_id, PDO::PARAM_INT);
        $stmt->bindParam(':source', $source, PDO::PARAM_STR);
        $stmt->execute();
    }
}
