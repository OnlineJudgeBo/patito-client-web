<?php

namespace PatitoOnlineJudge\Presentation\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }
}
