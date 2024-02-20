<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class RecoveryPasswordController
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

    public function userRecoveryPassword($userPost)
    {
        try {
            $email    = $userPost["email"];
            $this->loginService->userRecoveryPassword($email);
            header("Location: login.php");
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            header('Content-Type: application/json; charset=utf-8');
            header("HTTP/1.1 400 Bad Request");
        }
    }

    public function render()
    {
        $title = $this->title;
        require_once __DIR__ . "/../Views/lostpassword.php";
    }
}
