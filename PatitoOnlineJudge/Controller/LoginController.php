<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\LoginService;

class LoginController
{
    private $contestService;
    public $view_title;

    public function __construct(LoginService $loginService)
    {
        $this->view_title = "Contests";
        $this->contestService = $loginService;
    }

    public function render()
    {
        require_once __DIR__ . "/../../resources/View/login.php";
    }
}
