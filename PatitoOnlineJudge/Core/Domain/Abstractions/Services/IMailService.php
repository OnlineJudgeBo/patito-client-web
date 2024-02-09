<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IMailService
{

    public function sendRecoveryPasswordEmail($email, $token);

}
