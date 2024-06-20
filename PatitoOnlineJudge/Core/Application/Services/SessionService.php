<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISessionService;

class SessionService implements ISessionService
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getSessionId(): string
    {
        return session_id();
    }

    public function setSessionVariable(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getSessionVariable(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function getUserRole(): string
    {
        if (isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador") {
            return "Administrador";
        } elseif (isset($_SESSION["Docente"]) && $_SESSION["Docente"] == "Docente") {
            return "Docente";
        } elseif (isset($_SESSION["Auxiliar"]) && $_SESSION["Auxiliar"] == "Auxiliar") {
            return "Auxiliar";
        } else {
            return "Usuario";
        }
    }
}
