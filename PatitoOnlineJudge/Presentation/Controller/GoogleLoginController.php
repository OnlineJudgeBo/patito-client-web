<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Application\Exceptions\ApplicationException;
use PatitoOnlineJudge\Core\Application\Services\GoogleOAuthService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\UserDomainObject;
use PatitoOnlineJudge\Presentation\Utils\Utils;

// Sign in with Google: google-login.php sends the user to Google, google-callback.php signs them in,
// and google-register.php lets a new user pick a username.
class GoogleLoginController
{
    private $googleOAuthService;
    private $loginService;

    public function __construct(GoogleOAuthService $googleOAuthService, ILoginService $loginService)
    {
        $this->googleOAuthService = $googleOAuthService;
        $this->loginService = $loginService;
    }

    public function start()
    {
        if (!$this->googleOAuthService->isEnabled()) {
            $this->backToLogin("El inicio de sesión con Google no está disponible.");
        }
        $_SESSION["google_state"] = bin2hex(random_bytes(16));
        $this->redirect($this->googleOAuthService->authorizationUrl($_SESSION["google_state"], $this->redirectUri()));
    }

    public function callback()
    {
        $state = $_SESSION["google_state"] ?? "";
        unset($_SESSION["google_state"]);
        if ($state === "" || !hash_equals($state, (string)($_GET["state"] ?? "")) || empty($_GET["code"])) {
            // Also when the user cancels on Google's page.
            $this->backToLogin("No se completó el inicio de sesión con Google.");
        }

        try {
            $profile = $this->googleOAuthService->fetchProfile((string)$_GET["code"], $this->redirectUri());
            if ($this->loginService->authenticateByEmail($profile["email"])) {
                $this->redirect("index.php");
            }
        } catch (ApplicationException $e) {
            $this->backToLogin($e->getMessage());
        }

        $_SESSION["google_profile"] = $profile;
        $this->redirect("google-register.php");
    }

    public function register()
    {
        $profile = $_SESSION["google_profile"] ?? null;
        if (!$profile) {
            $this->redirect("login.php");
        }

        $error = null;
        $username = $this->suggestUsername($profile["email"]);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim((string)($_POST["username"] ?? ""));
            $user = new UserDomainObject();
            $user->userId = $username;
            $user->email = $profile["email"];
            $user->nick = trim((string)($_POST["name"] ?? "")) ?: $profile["name"];
            $user->lastname = trim((string)($_POST["lastname"] ?? "")) ?: $profile["lastname"];
            $user->ip = $_SERVER["REMOTE_ADDR"];
            try {
                $this->loginService->registerWithGoogle($user);
                unset($_SESSION["google_profile"]);
                $this->redirect("index.php");
            } catch (ApplicationException $e) {
                $error = $e->getMessage();
            }
        }

        $title = "Crea tu cuenta";
        // Only the patito theme has the Google button.
        require_once __DIR__ . "/../Views/patito/google-register.php";
    }

    private function suggestUsername(string $email): string
    {
        return substr(preg_replace('/[^A-Za-z0-9]/', '', strstr($email, "@", true)), 0, 20);
    }

    private function redirectUri(): string
    {
        return Utils::siteBaseUrl() . "/google-callback.php";
    }

    private function backToLogin(string $message)
    {
        $_SESSION["login_error"] = $message;
        $this->redirect("login.php");
    }

    private function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }
}
