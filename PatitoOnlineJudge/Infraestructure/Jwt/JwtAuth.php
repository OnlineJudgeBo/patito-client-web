<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{

    function generateTokens($userId)
    {
        $claveSecreta = "esta_es_mi_super_clave_secreta_zsx";
        $tiempoActual = time();
        $expirationTime = $tiempoActual + (60 * 60);
        $expirationTime = $tiempoActual + (60 * 60 * 24 * 30);

        $payloadAccessToken = [
            "sub" => $userId,
            "iat" => $tiempoActual,
            "exp" => $expirationTime
        ];

        $accessToken = JWT::encode($payloadAccessToken, $claveSecreta, 'HS256');

        $payloadRefreshToken = [
            "sub" => $userId,
            "iat" => $tiempoActual,
            "exp" => $expirationTime
        ];

        $refreshToken = JWT::encode($payloadRefreshToken, $claveSecreta, 'HS256');

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }
}
