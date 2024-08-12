<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class LoginRepository extends BaseRepository implements ILoginRepository
{
    public function getUser($username)
    {
        $query = "SELECT * FROM `users` WHERE `user_id` = :username AND is_deleted = 0";
        return $this->executeQuery($query, [':username' => $username]);
    }

    public function getUserProfile($username)
    {
        $query = "SELECT * FROM `user_profiles` WHERE `user_id` = :username";
        return $this->executeQuery($query, [':username' => $username]);
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM `user_profiles` WHERE `email` = :email";
        return $this->fetchAll($query, [':email' => $email]);
    }

    public function getPrivilege($username)
    {
        $query = "SELECT `contest_id` FROM `contest_user` WHERE `user_id` = :username";
        return $this->fetchAll($query, [':username' => $username]);
    }

    public function getAdminPrivilege($username)
    {
        $query = "SELECT user_id, role_name
                  FROM user_roles, roles
                  WHERE user_roles.role_id = roles.role_id
                  AND  user_roles.user_id = :user_id";
        return $this->fetchAll($query, [':user_id' => $username]);
    }

    public function updateUserLastLogin($username, $accesstime)
    {
        if ($accesstime == "0000-00-00 00:00:00") {
            $query = "UPDATE users SET accesstime = NOW() WHERE user_id = :user_id";
            $this->executeQuery($query, [':user_id' => $username]);
        }
    }

    public function logLoginAttempt($username)
    {
        $userIP = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO `loginlog` (`id`, `user_id`, `ip`, `time`) VALUES (NULL, :user_id, :ip, NOW())";
        $this->executeQuery($query, [
            ':user_id' => $username,
            ':ip' => $userIP
        ]);
    }

    public function registerUser(UserDomainObject $user)
    {   
        $this->beginTransaction();
        $sql = "INSERT INTO users (user_id, ip, accesstime, password, reg_time)
        VALUES (:user_id, :ip, NOW(), :password, NOW())";
        $params = [
            ':user_id' => $user->userId,
            ':ip' => $user->ip,
            ':password' => $user->password
        ];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_activity (user_id, submit, solved)
        VALUES (:user_id, 0, 0)";
        $params = [':user_id' => $user->userId];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_profiles (user_id, email, nick, school, lastname, pais_id)
        VALUES (:user_id, :email, :nick, :school, :lastname, :pais_id)";
        $params = [
            ':user_id' => $user->userId,
            ':email' => $user->email,
            ':nick' => $user->nick,
            ':school' => $user->school,
            ':lastname' => $user->lastname,
            ':pais_id' => $user->paisId
        ];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_settings (user_id, volume, language, obi, institucion_id)
        VALUES (:user_id, :volume, :language, :obi, :institucion_id)";
        $params = [
            ':user_id' => $user->userId,
            ':volume' => 0,
            ':language' => 1,
            ':obi' => 0,
            ':institucion_id' => -1
        ];
        $this->executeQuery($sql, $params);
        $this->commit();
    }

    public function existsByUserId($username)
    {
        $query = "SELECT COUNT(*) FROM users WHERE user_id = :username";
        return $this->fetchColumn($query, [':username' => $username]) > 0;
    }

    public function existsByEmail($email)
    {
        $query = "SELECT COUNT(*) FROM user_profiles WHERE email = :email";
        return $this->fetchColumn($query, [':email' => $email]) > 0;
    }

    public function isEmailAvailableForChange($email, $user_id)
    {
        $query = "SELECT COUNT(*) FROM user_profiles
                WHERE email = :email AND user_id != :currentUserId";
        return $this->fetchColumn($query, [
            ':email' => $email,
            ':currentUserId' => $user_id
        ]) > 0;
    }

    public function resetRecoveryPassword($user_id, $password)
    {
        $query = "UPDATE users SET reset_password_token =:reset_password_token,
                reset_password_expires = ADDTIME(NOW(), '01:00:00')
                WHERE user_id =:user_id";
        $this->executeQuery($query, [
            ':reset_password_token' => $password,
            ':user_id' => $user_id
        ]);
    }

    public function verifyTokenRecovery($token)
    {
        $query = "SELECT *
                FROM users
                WHERE reset_password_token = :token
                AND NOW() <= reset_password_expires";
        return $this->fetchColumn($query, [':token' => $token]) > 0;
    }

    public function updatePasswordByToken($password, $token)
    {
        $query = "UPDATE users SET reset_password_token = NULL,
                password = :password,
                reset_password_expires = NULL
                WHERE reset_password_token =:token";
        $this->executeQuery($query, [
            ':password' => $password,
            ':token' => $token
        ]);
    }

    public function updatePasswordByUserId($password, $user_id)
    {
        $query = "UPDATE users SET password = :password
                WHERE user_id =:user_id";
        $this->executeQuery($query, [
            ':password' => $password,
            ':user_id' => $user_id
        ]);
    }

    public function updateUserProfile($user_id, UserDomainObject $userData)
    {
        $query = "UPDATE user_profiles 
                SET email = :email, nick = :nick, lastname = :lastname
                WHERE user_id = :user_id";
        $this->executeQuery($query, [
            ':email' => $userData->email,
            ':nick' => $userData->nick,
            ':lastname' => $userData->lastname,
            ':user_id' => $user_id
        ]);
    }
}
