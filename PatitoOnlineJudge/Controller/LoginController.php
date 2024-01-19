<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\LoginService;

class LoginController
{
    private $loginService;
    public $view_title;

    public function __construct(LoginService $loginService)
    {
        $this->view_title = "Contests";
        $this->loginService = $loginService;
    }

    public function login($username, $password) {
       $this->loginService->authenticateUser($username, $password);
    }

    public function render()
    {
        require_once __DIR__ . "/../../resources/View/login.php";
    }
}
