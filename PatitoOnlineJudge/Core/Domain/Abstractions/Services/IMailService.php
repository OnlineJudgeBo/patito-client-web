<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

interface IMailService
{

    public function sendRecoveryPasswordEmail($email, $token, $userId);
    public function sendWelcomeEmail($email, UserDomainObject $userId);
}
