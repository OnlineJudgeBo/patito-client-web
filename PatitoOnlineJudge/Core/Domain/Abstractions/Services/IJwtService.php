<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IJwtService
{
    public function generateTokens($userId, $userRoles);

    /**
     * Verifies the token signature and expiration, and returns its decoded payload.
     * @throws \Exception if the token is malformed, has an invalid signature, or is expired.
     */
    public function verifyToken(string $token): array;
}
