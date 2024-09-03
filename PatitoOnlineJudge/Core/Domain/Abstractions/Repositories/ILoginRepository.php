<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface ILoginRepository
{

    public function getUser($username, $site_id);
    
    public function getUserByEmail($email, $site_id);

    public function getPrivilege($username, $site_id);

    public function getAdminPrivilege($username, $site_id);

    public function updateUserLastLogin($username, $accesstime, $site_id);

    public function logLoginAttempt($username, $site_id);

    public function registerUser(UserDomainObject $user, $site_id);

    public function existsByUserId($username, $site_id);

    public function existsByEmail($email, $site_id);

    public function resetRecoveryPassword($email, $passwod, $site_id);

    public function verifyTokenRecovery($token, $site_id);

    public function updatePasswordByToken($password, $token, $site_id);

    public function getUserProfile($username, $site_id);

    public function updateUserProfile($user_id, UserDomainObject $userData, $site_id);

    public function isEmailAvailableForChange($email, $user_id, $site_id);
}
