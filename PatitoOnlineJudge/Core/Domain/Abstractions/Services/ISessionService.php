<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ISessionService
{
    public function getSessionId(): string;
    public function setSessionVariable(string $key, $value): void;
    public function getSessionVariable(string $key);
    public function getUserRole(): string;
}
