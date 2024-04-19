<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    function generateTokens($userId)
    {
        $claveSecreta = "esta_es_mi_super_clave_secreta_zsx";
        $actualTime = time();
        $expirationTime = $actualTime + (60 * 60) * 2;
        $expirationTimeRefresh = $actualTime + (60 * 60 * 24 * 30);

        $payloadAccessToken = [
            "sub" => $userId,
            "iat" => $actualTime,
            "exp" => $expirationTime,
            "iss" => "TuValorDeIssuer",
            "aud" => "TuValorDeAudiencia"
        ];

        $accessToken = JWT::encode($payloadAccessToken, $claveSecreta, 'HS256');

        $payloadRefreshToken = [
            "sub" => $userId,
            "iat" => $actualTime,
            "exp" => $expirationTimeRefresh,
            "iss" => "TuValorDeIssuer",
            "aud" => "TuValorDeAudiencia"
        ];

        $refreshToken = JWT::encode($payloadRefreshToken, $claveSecreta, 'HS256');

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }
}
