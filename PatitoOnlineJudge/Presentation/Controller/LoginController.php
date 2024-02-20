<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;

class LoginController
{
    private $loginService;
    public $title;
    public $error;

    public function __construct(ILoginService $loginService)
    {
        $this->title = "Contests";
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
        $title = $this->title;
        extract(["error" => $this->error]);
        require_once __DIR__ . "/../Views//login.php";
    }
}
