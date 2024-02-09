<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;

class UpdatePasswordController
{
    private $loginService;
    public $view_title;
    public $error;

    public function __construct(ILoginService $loginService)
    {
        $this->view_title = "Contests";
        $this->loginService = $loginService;
        $this->error = "";
    }

    public function showPasswordPage($data)
    {
            $parts = parse_url($data);
            parse_str($parts['query'], $query);
            $token = $query['token'];
            $this->loginService->verifyToken($token);
    }

    public function render()
    {
        require_once __DIR__ . "/../Views/updatepassword.php";
    }
}
