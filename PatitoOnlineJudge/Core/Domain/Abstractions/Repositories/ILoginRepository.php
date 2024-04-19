<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface ILoginRepository
{

    public function getUser($username);
    
    public function getUserByEmail($email);

    public function getPrivilege($username);

    public function getAdminPrivilege($username);

    public function updateUserLastLogin($username, $accesstime);

    public function logLoginAttempt($username);

    public function registerUser(UserDomainObject $user);

    public function existsByUserId($username);

    public function existsByEmail($email);

    public function resetRecoveryPassword($email, $passwod);

    public function verifyTokenRecovey($token);

    public function updatePasswordByToken($password, $token);
}
