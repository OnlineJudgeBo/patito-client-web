<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\LoginRepository;

class LoginService
{
    protected $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }
}
