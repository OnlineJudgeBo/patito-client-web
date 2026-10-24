<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Application\Exceptions\ApplicationException;
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
                $this->signIn($user);
                return $user;
            }
        }
        throw new \Exception("Username o Password incorrectos");
    }

    // Google already checked the email, so the account that has it signs in. Returns false when no account has it.
    public function authenticateByEmail($email)
    {
        $users = [];
        foreach ($this->loginRepository->getUserByEmail($email, $this->site_id) as $profile) {
            $user = $this->loginRepository->getUser($profile['user_id'], $this->site_id);
            if (!empty($user)) {
                $users[] = $user;
            }
        }
        if (count($users) > 1) {
            // Old accounts can share an email; we can't tell which one is theirs.
            throw new ApplicationException("Hay varias cuentas con el correo {$email}. Entra con tu usuario y contraseña.");
        }
        if (empty($users)) {
            return false;
        }
        $this->signIn($users[0]);
        return $users[0];
    }

    // New account from a Google sign-in: it gets a random password and signs in with Google (or recovers a password later).
    public function registerWithGoogle(UserDomainObject $user)
    {
        $user->password = bin2hex(random_bytes(16));
        $this->registerUser($user);
        $this->signIn($this->loginRepository->getUser($user->userId, $this->site_id));
    }

    private function signIn($user)
    {
        session_regenerate_id(true);
        $this->loginRepository->updateUserLastLogin($user['user_id'], $user["accesstime"], $this->site_id);
        $this->loginRepository->logLoginAttempt($user['user_id'], $this->site_id);
        $this->startUserSession($user);
        $userRoles = $this->loginRepository->getAdminPrivilege($user['user_id'], $this->site_id);
        $tokens = $this->jwtService->generateTokens($user['user_id'], $userRoles);
        $isSecureRequest = $this->isSecureRequest();
        setcookie('accessToken', $tokens["accessToken"], 0, '/', '', $isSecureRequest, false);
        setcookie('refreshToken', $tokens["refreshToken"], 0, '/', '', $isSecureRequest, false);
        setcookie('user_id', $user['user_id'], 0, '/', '', $isSecureRequest, false);
    }

    public function refreshTokens($refreshToken)
    {
        if (empty($refreshToken)) {
            throw new \Exception("Falta el refresh token.");
        }

        try {
            $payload = $this->jwtService->verifyToken($refreshToken);
        } catch (\Exception $e) {
            throw new \Exception("Tu sesión venció. Inicia sesión nuevamente.");
        }

        $userId = $payload['sub'] ?? '';
        $user = $this->loginRepository->getUser($userId, $this->site_id);
        if (empty($user)) {
            throw new \Exception("Tu sesión venció. Inicia sesión nuevamente.");
        }

        $userRoles = $this->loginRepository->getAdminPrivilege($userId, $this->site_id);
        $tokens = $this->jwtService->generateTokens($userId, $userRoles);
        $isSecureRequest = $this->isSecureRequest();
        setcookie('accessToken', $tokens["accessToken"], 0, '/', '', $isSecureRequest, false);
        setcookie('refreshToken', $tokens["refreshToken"], 0, '/', '', $isSecureRequest, false);

        return $tokens;
    }

    public function registerUser(UserDomainObject $user) {
        $authService = new AuthService();
        $this->userValidator->validate($user);
        $user->password = $authService->generatePasswordHash($user->password);
        $this->loginRepository->registerUser($user, $this->site_id);

        $mail = new MailService();
        $mail->sendWelcomeEmailAsync($user->email, $user);
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


    private function isSecureRequest(): bool
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        return (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            || (($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') === 'on');
    }

    private function startUserSession($user)
    {
        $_SESSION['user_id'] = $user['user_id'];

        $profile = $this->loginRepository->getUserProfile($user['user_id'], $this->site_id);
        $_SESSION['user_display_name'] = $this->getUserDisplayName($profile ?: $user);

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

        $newUserId = trim((string)($user->newUserId ?? ''));
        if ($newUserId !== '' && $newUserId !== $userId) {
            $this->userValidator->validateNewUserId($newUserId, $userId);
            $this->loginRepository->renameUser($userId, $newUserId, $this->site_id);
            $userId = $newUserId;
            $user->userId = $newUserId;
            $this->startUserSession($this->loginRepository->getUser($userId, $this->site_id));
            // Old tokens carry the old id as "sub"; reissue them so the admin UI / IDE keep working.
            $tokens = $this->jwtService->generateTokens($userId, $this->loginRepository->getAdminPrivilege($userId, $this->site_id));
            $isSecureRequest = $this->isSecureRequest();
            setcookie('accessToken', $tokens["accessToken"], 0, '/', '', $isSecureRequest, false);
            setcookie('refreshToken', $tokens["refreshToken"], 0, '/', '', $isSecureRequest, false);
            setcookie('user_id', $userId, 0, '/', '', $isSecureRequest, false);
        }

        $this->loginRepository->updateUserProfile($userId, $user, $this->site_id);
        $_SESSION['user_display_name'] = $this->getUserDisplayName([
            'user_id' => $userId,
            'nick' => $user->nick,
            'lastname' => $user->lastname,
        ]);
    }

    private function getUserDisplayName($user): string
    {
        $fullName = trim(($user['nick'] ?? '') . ' ' . ($user['lastname'] ?? ''));

        return $fullName !== '' ? $fullName : (string)($user['user_id'] ?? '');
    }
}
