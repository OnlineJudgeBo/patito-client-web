<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\LoginService;

class LoginController
{
    private $loginService;
    public $view_title;
    public $error;

    public function __construct(LoginService $loginService)
    {
        $this->view_title = "Contests";
        $this->loginService = $loginService;
        $this->error = "";
    }

    public function login($username, $password)
    {
        try {
            $this->loginService->authenticateUser($username, $password);
            header('Location: index.php');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        extract(["error" => $this->error]);
        require_once __DIR__ . "/../Presentation//login.php";
    }
}
