<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;
use PatitoOnlineJudge\Presentation\Utils\Utils;

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
        } catch (\Exception $e) {
            // Same answer whether or not the email has an account, so accounts can't be enumerated.
            error_log("Password recovery failed: " . $e->getMessage());
        }
        header("Location: login.php");
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        require_once $current_theme . "/lostpassword.php";
    }
}
