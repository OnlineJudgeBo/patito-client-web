<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

class LoginRepository {
    private $pdo;

    public function __construct(DatabaseConnector $connector) {
        $this->pdo = $connector->getConnection();
    }
}
