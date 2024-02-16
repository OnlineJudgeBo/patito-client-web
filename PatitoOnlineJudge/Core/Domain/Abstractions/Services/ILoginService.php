<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface ILoginService
{
    public function authenticateUser($username, $password);
    public function registerUser(UserDomainObject $user);
    public function userRecoveryPassword($email);
    public function verifyToken($token);
    public function updatePasswordByToken($password, $token);
}
