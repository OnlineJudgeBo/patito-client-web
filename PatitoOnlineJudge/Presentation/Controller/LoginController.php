<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ILoginService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class LoginController
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

    public function login($username, $password)
    {
        try {
            $this->loginService->authenticateUser($username, $password);
            header('Location: index.php');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function refresh()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input') ?: '', true) ?: [];
        $refreshToken = $input['refreshToken'] ?? ($_COOKIE['refreshToken'] ?? '');

        try {
            $tokens = $this->loginService->refreshTokens($refreshToken);
            http_response_code(200);
            echo json_encode(['accessToken' => $tokens['accessToken']]);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        extract(["error" => $this->error]);
        require_once $current_theme . "/login.php";
    }
}
