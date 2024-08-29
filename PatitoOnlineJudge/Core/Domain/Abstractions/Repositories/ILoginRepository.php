<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface ILoginRepository
{

    public function getUser($username, $realm);
    
    public function getUserByEmail($email, $realm);

    public function getPrivilege($username, $realm);

    public function getAdminPrivilege($username, $realm);

    public function updateUserLastLogin($username, $accesstime, $realm);

    public function logLoginAttempt($username, $realm);

    public function registerUser(UserDomainObject $user);

    public function existsByUserId($username, $realm);

    public function existsByEmail($email, $realm);

    public function resetRecoveryPassword($email, $passwod, $realm);

    public function verifyTokenRecovery($token, $realm);

    public function updatePasswordByToken($password, $token, $realm);

    public function getUserProfile($username, $realm);

    public function updateUserProfile($user_id, UserDomainObject $userData);

    public function isEmailAvailableForChange($email, $user_id, $realm);
}
