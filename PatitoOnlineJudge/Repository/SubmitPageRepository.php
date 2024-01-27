<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

class SubmitPageRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

}
