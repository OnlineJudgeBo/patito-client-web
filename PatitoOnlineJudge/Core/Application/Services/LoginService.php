<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class LoginService implements ILoginService
{
    protected $loginRepository;
    protected $userValidator;

    public function __construct(ILoginRepository $loginRepository, UserValidator $userValidator)
    {
        $this->loginRepository = $loginRepository;
        $this->userValidator = $userValidator;
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

    public function registerUser(UserDomainObject $user) {
        $authService = new AuthService();
        $this->userValidator->validate($user);
        $user->password = $authService->generatePasswordHash($user->password);
        $this->loginRepository->registerUser($user);
    }

    public function updatePasswordByToken($password, $token) {
        $authService = new AuthService();
        $passwordHash = $authService->generatePasswordHash($password);
        $this->loginRepository->updatePasswordByToken($passwordHash, $token);
    }

    public function userRecoveryPassword($email)
    {
        $authService = new AuthService();
        $encodePassword = $authService->generatePasswordHash($email);
        $this->loginRepository->resetRecoveryPassword($email, $encodePassword);

        $mail = new MailService();
        $mail->sendRecoveryPasswordEmail($email, $encodePassword);
    }

    private function startUserSession($user)
    {
        $_SESSION['user_id'] = $user['user_id'];
        foreach ($this->loginRepository->getPrivilege($user['user_id']) as $rightstr) {
            $_SESSION[$rightstr["rightstr"]] = true;
        }
    }

    public function verifyToken($token)
    {
        if (!$this->loginRepository->verifyTokenRecovey($token)) {
            throw new \Exception("El token no es correcto o ya expiro.");
        }
    }
}
