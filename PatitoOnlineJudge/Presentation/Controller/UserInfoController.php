<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IUserInfoService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class UserInfoController
{
    private $userInfoService;
    private $loginService;
    public $title;
    public $error;
    public $userId;
    private $token;

    public function __construct(
        IUserInfoService $userInfoService,
        ILoginService $loginService,
    ) {
        $this->title = "Mis problemas resueltos.";
        $this->userInfoService = $userInfoService;
        $this->loginService = $loginService;
        $this->error = "";
    }

    public function addUserId($userId)
    {
        $this->userId = $userId;
    }

    public function updateUserProfile($data)
    {
        $user = new UserDomainObject();
        $user->email    = $data["email"];
        $user->nick     = $data["nick"];
        $user->lastname = $data["lastname"];
        $user->userId   = $this->userId;
        $user->password = $data["password"];

        $this->loginService->updateUserProfile($this->userId, $user);
        header('Location: ./userInfo.php');
        exit;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $problemList = $this->userInfoService->getSummarySolutions($this->userId, "ac");
        $problemErrorList = $this->userInfoService->getSummarySolutions($this->userId, "error");
        $user = $this->loginService->getMe($this->userId);
        $userDisplayName = trim(($user['nick'] ?? '') . ' ' . ($user['lastname'] ?? ''));
        $userDisplayName = $userDisplayName !== '' ? $userDisplayName : (string)$this->userId;
        $_SESSION['user_display_name'] = $userDisplayName;
        $title = $this->title;
        require_once $current_theme . "/userInfo.php";
    }
}
