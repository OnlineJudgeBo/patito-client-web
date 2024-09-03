<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IJwtService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;

class LoginService implements ILoginService
{
    protected $loginRepository;
    protected $userValidator;
    protected $jwtService;
    private $site_id;

    public function __construct(
        ILoginRepository $loginRepository,
        IJwtService $jwtService,
        UserValidator $userValidator)
    {
        $this->loginRepository = $loginRepository;
        $this->jwtService = $jwtService;
        $this->userValidator = $userValidator;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function authenticateUser($username, $password)
    {
        $user = $this->loginRepository->getUser($username, $this->site_id);

        if (!empty($user)) {
            $authService = new AuthService();
            if ($authService->verifyPassword($password, $user["password"])) {
                $this->loginRepository->updateUserLastLogin($user['user_id'], $user["accesstime"], $this->site_id);
                $this->loginRepository->logLoginAttempt($user['user_id'], $this->site_id);
                $this->startUserSession($user);
                $userRoles = $this->loginRepository->getAdminPrivilege($user['user_id'], $this->site_id);
                $tokens = $this->jwtService->generateTokens($user['user_id'], $userRoles);
                setcookie('accessToken', $tokens["accessToken"], 0, '/', '', true, false);
                setcookie('refreshToken', $tokens["refreshToken"], 0, '/', '', true, false);
                setcookie('user_id', $user['user_id'], 0, '/', '', true, false);
                return $user;
            }
        }
        throw new \Exception("Username o Password incorrectos");
    }

    public function registerUser(UserDomainObject $user) {
        $authService = new AuthService();
        $this->userValidator->validate($user);
        $user->password = $authService->generatePasswordHash($user->password);
        $this->loginRepository->registerUser($user, $this->site_id);

        $mail = new MailService();
        $mail->sendWelcomeEmail($user->email, $user);
    }

    public function updatePasswordByToken($password, $token) {
        $authService = new AuthService();
        $passwordHash = $authService->generatePasswordHash($password);
        $this->loginRepository->updatePasswordByToken($passwordHash, $token, $this->site_id);
    }

    public function userRecoveryPassword($email)
    {
        $user = $this->loginRepository->getUserByEmail($email, $this->site_id);

        if (count($user) > 2) {
            throw new \Exception("Se han encontrado múltiples registros asociados a su correo electrónico. 
                                Por favor, póngase en contacto con el administrador para resolver este problema.");
        }

        if ($this->loginRepository->existsByEmail($email, $this->site_id)) {
            $authService = new AuthService();
            $encodePassword = $authService->generatePasswordHash($email);
            $this->loginRepository->resetRecoveryPassword($user[0]["user_id"], $encodePassword, $this->site_id);
            
            $mail = new MailService();
            $mail->sendRecoveryPasswordEmail($email, $encodePassword, $user);
        } else {
            throw new \Exception("El correo electrónico no tiene cuenta en el Juez Virtual");
        }
    }

    private function startUserSession($user)
    {
        $_SESSION['user_id'] = $user['user_id'];
        foreach ($this->loginRepository->getPrivilege($user['user_id'], $this->site_id) as $rightstr) {
            $_SESSION["c".$rightstr["contest_id"]] = true;
        }

        foreach ($this->loginRepository->getAdminPrivilege($user['user_id'], $this->site_id) as $rightstr) {
            $_SESSION[$rightstr["role_name"]] = $rightstr["role_name"];
        }
    }

    public function getMe($userId)
    {
        return $this->loginRepository->getUserProfile($userId, $this->site_id);
    }

    public function verifyToken($token)
    {
        if (!$this->loginRepository->verifyTokenRecovery($token, $this->site_id)) {
            throw new \Exception("El token no es correcto o ya expiro.");
        }
    }

    public function updateUserProfile($userId, UserDomainObject $user) {
        if (!empty($user->password)) {
            $userEmail = $this->getMe($userId);
            $this->userRecoveryPassword($userEmail["email"]);
        }
        $this->userValidator->validateProfileToUpdate($user, $this->site_id);
        $this->loginRepository->updateUserProfile($userId, $user, $this->site_id);
    }
}
