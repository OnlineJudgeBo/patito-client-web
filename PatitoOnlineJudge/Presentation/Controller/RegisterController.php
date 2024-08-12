<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Application\Exceptions\ApplicationException;
use PatitoOnlineJudge\Core\Application\Services\NotificationErrorService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INotificationErrorService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class RegisterController
{
    private $loginService;
    private $notificationErrorService;
    public $title;
    public $error;

    public function __construct(ILoginService $loginService, INotificationErrorService $notificationErrorService)
    {
        $this->title = "Contests";
        $this->loginService = $loginService;
        $this->notificationErrorService = $notificationErrorService;
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
        } catch (ApplicationException $e) {
            echo $e->getMessage();
            header('Content-Type: application/json; charset=utf-8');
            header("HTTP/1.1 400 Bad Request");
        } catch (\Exception $e) {
            $this->notificationErrorService->notifyError($e);
            echo "Error al registrar el usuario";
            header('Content-Type: application/json; charset=utf-8');
            header("HTTP/1.1 400 Bad Request");
        } 
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        require_once $current_theme . "/register.php";
    }
}
