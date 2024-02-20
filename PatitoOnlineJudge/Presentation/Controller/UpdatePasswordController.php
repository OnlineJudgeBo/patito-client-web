<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;

class UpdatePasswordController
{
    private $loginService;
    public $title;
    public $error;
    private $token;

    public function __construct(ILoginService $loginService)
    {
        $this->title = "Contests";
        $this->loginService = $loginService;
        $this->error = "";
    }

    public function showPasswordPage($data)
    {
            $parts = parse_url($data);
            parse_str($parts['query'], $query);
            $token = $query['token'];
            $this->token = $token;
            $this->loginService->verifyToken($token);
    }

    public function updatePasswordByToken($request)
    {
        $password = $request["password"];
        $token = $request["token"];
        $this->loginService->verifyToken($token);
        $this->loginService->updatePasswordByToken($password, $token);
    }

    public function render()
    {
        $title = $this->title;
        $token = $this->token;
        require_once __DIR__ . "/../Views/updatepassword.php";
    }
}
