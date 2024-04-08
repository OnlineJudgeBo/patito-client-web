<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;
use PDO;

class LoginRepository implements ILoginRepository
{
    private $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    public function getUser($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `user_id` = :username AND is_deleted = 0");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `email` = :email AND is_deleted = 0");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrivilege($username)
    {
        $sql = "SELECT `rightstr` FROM `privilege` WHERE `user_id` = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserLastLogin($username, $accesstime)
    {
        if ($accesstime == "0000-00-00 00:00:00") {
            $sql = "UPDATE users SET accesstime = NOW() WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':user_id' => $username]);
        }
    }

    public function logLoginAttempt($username)
    {
        $userIP = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO `loginlog` (`id`, `user_id`, `ip`, `time`) VALUES (NULL, :user_id, :ip, NOW())";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $username,
            ':ip' => $userIP
        ]);
    }

    public function registerUser(UserDomainObject $user)
    {
        $sql = "INSERT INTO users (user_id, email, ip, accesstime, password, reg_time, nick, school, lastname, pais_id, obi, institucion_id)
        VALUES (:user_id, :email, :ip, NOW(), :password, NOW(), :nick, :school, :lastname, :pais_id, :obi, :institucion_id)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':user_id', $user->userId, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':ip', $user->ip, PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->password, PDO::PARAM_STR);
        $stmt->bindValue(':nick', $user->nick, PDO::PARAM_STR);
        $stmt->bindValue(':school', $user->school, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $user->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':pais_id', $user->paisId, PDO::PARAM_STR);
        $stmt->bindValue(':obi', $user->obi, PDO::PARAM_STR);
        $stmt->bindValue(':institucion_id', $user->institucionId, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function existsByUserId($username) {
        $query = "SELECT COUNT(*) FROM users WHERE user_id = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function existsByEmail($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function resetRecoveryPassword($email, $password) {
        $sql=" UPDATE users SET reset_password_token =:reset_password_token,
                reset_password_expires = ADDTIME(NOW(), '00:15:00')
                WHERE email =:email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['reset_password_token' => $password, 'email' => $email]);
    }

    public function verifyTokenRecovey($token) {
        $stmt = $this->pdo->prepare("SELECT *
                                    FROM users
                                    WHERE reset_password_token = :token
                                    AND NOW() <= reset_password_expires");
        $stmt->execute([':token' => $token]);
        return $stmt->fetchColumn() > 0;
    }

    public function updatePasswordByToken($password, $token) {
        $sql=" UPDATE users SET reset_password_token = NULL,
                password = :password,
                reset_password_expires = NULL
                WHERE reset_password_token =:token";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['password' => $password, 'token' => $token]);
    }
}
