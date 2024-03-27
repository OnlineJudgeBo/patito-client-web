<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IJwtService;

require __DIR__."/../../../Infraestructure/Jwt/JwtAuth.php";

class JwtService implements IJwtService
{
    private $jwtAuth;

    public function __construct()
    {
        $this->jwtAuth = new \JwtAuth();
    }

    public function generateTokens($userId) {
        return $this->jwtAuth->generateTokens($userId);
    }
}
