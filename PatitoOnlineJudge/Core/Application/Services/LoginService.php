<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;

class LoginService implements ILoginService
{
    protected $loginRepository;

    public function __construct(ILoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function authenticateUser($username, $password)
    {
        $user = $this->loginRepository->getUser($username);

        if (!empty($user)) {
            $authService = new AuthService();
            if ($authService->verifyPassword($password, $user["password"])) {
                $this->loginRepository->updateUserLastLogin($user['user_id'], $user["accesstime"]);
                $this->loginRepository->logLoginAttempt($user['user_id']);
                $this->startUserSession($user);
                return $user;
            }
        }
        throw new \Exception("Username o Password incorrectos");
    }

    private function startUserSession($user)
    {
        $_SESSION['user_id'] = $user['user_id'];
        foreach ($this->loginRepository->getPrivilege($user['user_id']) as $rightstr) {
            $_SESSION[$rightstr["rightstr"]] = true;
        }
    }
}
