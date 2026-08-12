<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface ILoginService
{
    public function authenticateUser($username, $password);
    public function refreshTokens($refreshToken);
    public function registerUser(UserDomainObject $user);
    public function userRecoveryPassword($email);
    public function verifyToken($token);
    public function updatePasswordByToken($password, $token);
    public function getMe($userId);
    public function updateUserProfile($userId, UserDomainObject $user);
}
