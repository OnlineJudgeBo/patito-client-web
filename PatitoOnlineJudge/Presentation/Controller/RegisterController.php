<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class RegisterController
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

    public function userRegister($userPost)
    {
        try {
            $email    = $userPost["email"];
            $lastname = $userPost["lastname"];
            $name     = $userPost["name"];
            $nickname = $userPost["nickname"];
            $password = $userPost["password"];
        
            $user = new UserDomainObject();
            $user->email    = $email;
            $user->nick     = $name;
            $user->lastname = $lastname;
            $user->userId   = $nickname;
            $user->password = $password;
            $user->ip       = $_SERVER["REMOTE_ADDR"];
            $user->institucionId = -1;

            $this->loginService->registerUser($user);
            echo json_encode("OK");
            header('Content-Type: application/json; charset=utf-8');
            header("HTTP/1.1 201 Created");
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
            header('Content-Type: application/json; charset=utf-8');
            header("HTTP/1.1 400 Bad Request");
        }
    }

    public function render()
    {
        $title = $this->title;
        require_once __DIR__ . "/../Views/register.php";
    }
}
