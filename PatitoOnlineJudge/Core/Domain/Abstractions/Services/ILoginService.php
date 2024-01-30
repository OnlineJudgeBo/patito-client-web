<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ILoginService
{
    public function authenticateUser($username, $password);

}
