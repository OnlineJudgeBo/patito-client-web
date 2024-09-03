<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class LoginRepository extends BaseRepository implements ILoginRepository
{
    public function getUser($username, $site_id)
    {
        $query = "SELECT * FROM users WHERE user_id = :username AND is_deleted = 0 AND site_id = :site_id";
        $params = [
            ':username' => $username,
            ':site_id' => $site_id
        ];
        return $this->executeQuery($query, $params);
    }

    public function getUserProfile($username, $site_id)
    {
        $query = "SELECT * FROM user_profiles WHERE user_id = :username AND site_id = :site_id";
        $params = [
            ':username' => $username,
            ':site_id' => $site_id
        ];
        return $this->executeQuery($query, $params);
    }

    public function getUserByEmail($email, $site_id)
    {
        $query = "SELECT * FROM user_profiles WHERE email = :email AND site_id = :site_id";
        $params = [
            ':email' => $email,
            ':site_id' => $site_id
        ];
        return $this->fetchAll($query, $params);
    }

    public function getPrivilege($username, $site_id)
    {
        $query = "SELECT contest_id FROM contest_user WHERE user_id = :username AND site_id = :site_id";
        $params = [
            ':username' => $username,
            ':site_id' => $site_id
        ];
        return $this->fetchAll($query, $params);
    }

    public function getAdminPrivilege($username, $site_id)
    {
        $query = "SELECT user_id, site_id, role_name
                  FROM user_roles, roles
                  WHERE user_roles.role_id = roles.role_id
                  AND  user_roles.user_id = :user_id
                  AND  user_roles.site_id = :site_id";
        $params = [
            ':user_id' => $username,
            ':site_id' => $site_id
        ];
        return $this->fetchAll($query, $params);
    }

    public function updateUserLastLogin($username, $accesstime, $site_id)
    {
        if ($accesstime == "0000-00-00 00:00:00") {
            $query = "UPDATE users SET accesstime = NOW() WHERE user_id = :user_id AND site_id = :site_id";
            $params = [
                ':user_id' => $username,
                ':site_id' => $site_id
            ];
            $this->executeQuery($query, $params);
        }
    }

    public function logLoginAttempt($username, $site_id)
    {
        $userIP = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO loginlog (user_id, ip, time, site_id) VALUES (:user_id, :ip, NOW(), :site_id)";
        $this->executeQuery($query, [
            ':user_id' => $username,
            ':ip' => $userIP,
            ':site_id' => $site_id
        ]);
    }

    public function registerUser(UserDomainObject $user, $site_id)
    {
        $this->beginTransaction();
        $sql = "INSERT INTO users (user_id, site_id, ip, accesstime, password, reg_time)
        VALUES (:user_id, :site_id, :ip, NOW(), :password, NOW())";
        $params = [
            ':user_id' => $user->userId,
            ':site_id' => $site_id,
            ':ip' => $user->ip,
            ':password' => $user->password
        ];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_activity (user_id, site_id, submit, solved)
        VALUES (:user_id, :site_id, 0, 0)";
        $params = [
            ':user_id' => $user->userId,
            ':site_id' => $site_id
        ];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_profiles (user_id, email, nick, school, lastname, pais_id, site_id)
        VALUES (:user_id, :email, :nick, :school, :lastname, :pais_id, :site_id)";
        $params = [
            ':user_id' => $user->userId,
            ':email' => $user->email,
            ':nick' => $user->nick,
            ':school' => $user->school,
            ':lastname' => $user->lastname,
            ':pais_id' => $user->paisId,
            ':site_id' => $site_id
        ];
        $this->executeQuery($sql, $params);

        $sql = "INSERT INTO user_settings (user_id, volume, language, obi, institucion_id, site_id)
        VALUES (:user_id, :volume, :language, :obi, :institucion_id, :site_id)";
        $params = [
            ':user_id' => $user->userId,
            ':volume' => 0,
            ':language' => 1,
            ':obi' => 0,
            ':institucion_id' => -1,
            ':site_id' => $site_id
        ];
        $this->executeQuery($sql, $params);
        $this->commit();
    }

    public function existsByUserId($username, $site_id)
    {
        $query = "SELECT COUNT(*) FROM users WHERE user_id = :username AND site_id = :site_id";
        $params = [
            ':username' => $username,
            ':site_id' => $site_id
        ];
        return $this->fetchColumn($query, $params) > 0;
    }

    public function existsByEmail($email, $site_id)
    {
        $query = "SELECT COUNT(*) FROM user_profiles WHERE email = :email AND site_id = :site_id";
        $params = [
            ':email' => $email,
            ':site_id' => $site_id
        ];
        return $this->fetchColumn($query, $params) > 0;
    }

    public function isEmailAvailableForChange($email, $user_id, $site_id)
    {
        $query = "SELECT COUNT(*) FROM user_profiles
                    WHERE email = :email AND user_id != :currentUserId
                    AND site_id = :site_id";
        $params = [
            ':email' => $email,
            ':currentUserId' => $user_id,
            ':site_id' => $site_id
        ];
        return $this->fetchColumn($query, $params) > 0;
    }

    public function resetRecoveryPassword($user_id, $password, $site_id)
    {
        $query = "UPDATE users SET reset_password_token =:reset_password_token,
                reset_password_expires = ADDTIME(NOW(), '01:00:00')
                WHERE user_id =:user_id AND site_id = :site_id";
        $params = [
            ':reset_password_token' => $password,
            ':user_id' => $user_id,
            ':site_id' => $site_id
        ];
        $this->executeQuery($query, $params);
    }

    public function verifyTokenRecovery($token, $site_id)
    {
        $query = "SELECT *
                FROM users
                WHERE reset_password_token = :token
                AND NOW() <= reset_password_expires
                AND site_id = :site_id";
        $params = [
            ':token' => $token,
            ':site_id' => $site_id
        ];
        return $this->fetchColumn($query, $params) > 0;
    }

    public function updatePasswordByToken($password, $token, $site_id)
    {
        $query = "UPDATE users SET reset_password_token = NULL,
                password = :password,
                reset_password_expires = NULL
                WHERE reset_password_token =:token
                AND site_id = :site_id";

        $this->executeQuery($query, [
            ':password' => $password,
            ':token' => $token,
            ':site_id' => $site_id

        ]);
    }

    public function updatePasswordByUserId($password, $user_id, $site_id)
    {
        $query = "UPDATE users SET password = :password
                WHERE user_id =:user_id
                AND site_id = :site_id";
        $params = [
            ':password' => $password,
            ':site_id' => $site_id,
            ':user_id' => $user_id
        ];
        $this->executeQuery($query, $params);
    }

    public function updateUserProfile($user_id, UserDomainObject $userData, $site_id)
    {
        $query = "UPDATE user_profiles
                SET email = :email, nick = :nick, lastname = :lastname
                WHERE user_id = :user_id AND site_id =:site_id";
        $this->executeQuery($query, [
            ':email' => $userData->email,
            ':nick' => $userData->nick,
            ':lastname' => $userData->lastname,
            ':user_id' => $user_id,
            ':site_id' => $site_id
        ]);
    }
}
