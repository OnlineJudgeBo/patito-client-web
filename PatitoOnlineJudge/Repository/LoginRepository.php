<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

class LoginRepository {
    private $pdo;

    public function __construct(DatabaseConnector $connector) {
        $this->pdo = $connector->getConnection();
    }

    public function getUser($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `user_id` = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPrivilege($username) {
        $sql = "SELECT `rightstr` FROM `privilege` WHERE `user_id` = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserLastLogin($username, $accesstime) {
        if ($accesstime == "0000-00-00 00:00:00") {
            $sql = "UPDATE users SET accesstime = NOW() WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $username]);
        }
    }

    public function logLoginAttempt($username) {
        $userIP = $_SERVER['REMOTE_ADDR'];
    
        $sql = "INSERT INTO `loginlog` (`id`, `user_id`, `ip`, `time`) VALUES (NULL, :user_id, :ip, NOW())";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->execute([
            ':user_id' => $username,
            ':ip' => $userIP
        ]);
    }
}
