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

    public function getUserProfile($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `user_profiles` WHERE `user_id` = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `user_profiles` WHERE `email` = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrivilege($username)
    {
        $sql = "SELECT `contest_id` FROM `contest_user` WHERE `user_id` = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminPrivilege($username)
    {
        $sql = "SELECT user_id, role_name 
        FROM user_roles, roles
        WHERE user_roles.role_id = roles.role_id
        AND  user_roles.user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $username]);
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
        $sql = "INSERT INTO users (user_id, ip, accesstime, password, reg_time)
        VALUES (:user_id, :ip, NOW(), :password, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user->userId, PDO::PARAM_STR);
        $stmt->bindValue(':ip', $user->ip, PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->password, PDO::PARAM_STR);
        $stmt->execute();

        $sql = "INSERT INTO user_activity (user_id, submit, solved)
        VALUES (:user_id, 0, 0)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user->userId, PDO::PARAM_STR);
        $stmt->execute();

        $sql = "INSERT INTO user_profiles (user_id, email, nick, school, lastname, pais_id)
        VALUES (:user_id, :email, :nick, :school, :lastname, :pais_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user->userId, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $stmt->bindValue(':nick', $user->nick, PDO::PARAM_STR);
        $stmt->bindValue(':school', $user->school, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $user->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':pais_id', $user->paisId, PDO::PARAM_STR);
        $stmt->execute();

        $sql = "INSERT INTO user_settings (user_id, volume, language, obi, institucion_id)
        VALUES (:user_id, :volume, :language, :obi, :institucion_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user->userId, PDO::PARAM_STR);
        $stmt->bindValue(':volume', "0", PDO::PARAM_INT);
        $stmt->bindValue(':language', "1", PDO::PARAM_INT);
        $stmt->bindValue(':obi', "0", PDO::PARAM_INT);
        $stmt->bindValue(':institucion_id', "-1", PDO::PARAM_INT);
        $stmt->execute();
    }

    public function existsByUserId($username) {
        $query = "SELECT COUNT(*) FROM users WHERE user_id = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function existsByEmail($email) {
        $query = "SELECT COUNT(*) FROM user_profiles WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function isEmailAvailableForChange($email, $user_id) {
        $query = "SELECT COUNT(*) FROM user_profiles WHERE email = :email AND user_id != :currentUserId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':email', $user_id, PDO::PARAM_STR);
        return $stmt->fetchColumn() > 0;
    }

    public function resetRecoveryPassword($user_id, $password) {
        $sql=" UPDATE users SET reset_password_token =:reset_password_token,
                reset_password_expires = ADDTIME(NOW(), '01:00:00')
                WHERE user_id =:user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['reset_password_token' => $password, 'user_id' => $user_id]);
    }

    public function verifyTokenRecovery($token) {
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

    public function updatePasswordByUserId($password, $user_id) {
        $sql=" UPDATE users SET password = :password
                WHERE user_id =:user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['password' => $password, 'user_id' => $user_id]);
    }

    public function updateUserProfile($user_id, UserDomainObject $userData)
    {
        $sql = "UPDATE user_profiles 
        SET email = :email, nick = :nick, lastname = :lastname
        WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':email', $userData->email, PDO::PARAM_STR);
        $stmt->bindValue(':nick', $userData->nick, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $userData->lastname, PDO::PARAM_STR);
        $stmt->execute();
    }
}
