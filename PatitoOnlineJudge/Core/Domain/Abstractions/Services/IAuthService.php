<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IAuthService
{

    public function generatePasswordHash($password, $isMd5);

    public function verifyPassword($password, $savedHash);
}
