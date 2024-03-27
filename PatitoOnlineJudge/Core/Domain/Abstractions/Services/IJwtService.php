<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IJwtService
{
    public function generateTokens($userId);
}
