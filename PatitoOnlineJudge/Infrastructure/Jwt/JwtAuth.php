<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    function generateTokens($userId, array $userRoles)
    {
        $claveSecreta = "esta_es_mi_super_clave_secreta_zsx";
        $actualTime = time();
        $expirationTime = $actualTime + (60 * 60) * 1; //1h
        $expirationTimeRefresh = $actualTime + (60 * 60 * 24 * 30);

        $payloadAccessToken = [
            "sub"     => $userId,
            "roles"   => implode(",", array_column($userRoles, 'role_name')),
            "iat"     => $actualTime,
            "exp"     => $expirationTime,
            "iss"     => $_SERVER["JWT_ISS"],
            "aud"     => $_SERVER["JWT_AUD"],
            "site_id" => $_SERVER["SITE_ID"]
        ];

        $accessToken = JWT::encode($payloadAccessToken, $claveSecreta, 'HS256');

        $payloadRefreshToken = [
            "sub"     => $userId,
            "roles"   => implode(",", array_column($userRoles, 'role_name')),
            "iat"     => $actualTime,
            "exp"     => $expirationTimeRefresh,
            "iss"     => $_SERVER["JWT_ISS"],
            "aud"     => $_SERVER["JWT_AUD"],
            "site_id" => $_SERVER["SITE_ID"]
        ];

        $refreshToken = JWT::encode($payloadRefreshToken, $claveSecreta, 'HS256');

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }
}
