<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Application\Exceptions\ApplicationException;
use PatitoOnlineJudge\Core\Application\Services\GoogleOAuthService;
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

            $this->loginService->registerUser($user);
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(201);
            echo json_encode("OK");
        } catch (ApplicationException $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo $e->getMessage();
        } catch (\Exception $e) {
            $this->notificationErrorService->notifyError($e);
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo "Error al registrar el usuario";
        } 
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $googleEnabled = (new GoogleOAuthService())->isEnabled();
        require_once $current_theme . "/register.php";
    }
}
