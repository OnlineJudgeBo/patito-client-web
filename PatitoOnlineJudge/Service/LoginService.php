<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\LoginRepository;

class LoginService
{
    protected $loginRepository;

    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function authenticateUser($username, $password) {
        $user = $this->loginRepository->getUser($username);
        
        if (!empty($user)) {
            $authService = new AuthService();
            if ($authService->verifyPassword($password, $user["password"])) {
                $this->loginRepository->updateUserLastLogin($user['user_id'], $user["accesstime"]);
                $this->loginRepository->logLoginAttempt($user['user_id']);
                $this->startUserSession($user);
            }
            return $user;
        }
    }

    private function startUserSession($user) {
        $_SESSION['user_id'] = $user['user_id'];
        foreach($this->loginRepository->getPrivilege($user['user_id']) as $rightstr) {
            $_SESSION[$rightstr["rightstr"]] = true;
        }
    }
}
