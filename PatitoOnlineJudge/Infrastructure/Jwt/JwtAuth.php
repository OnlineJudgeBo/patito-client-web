<?php

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

        $accessToken = $this->encodeJwt($payloadAccessToken, $claveSecreta);

        $payloadRefreshToken = [
            "sub"     => $userId,
            "roles"   => implode(",", array_column($userRoles, 'role_name')),
            "iat"     => $actualTime,
            "exp"     => $expirationTimeRefresh,
            "iss"     => $_SERVER["JWT_ISS"],
            "aud"     => $_SERVER["JWT_AUD"],
            "site_id" => $_SERVER["SITE_ID"]
        ];

        $refreshToken = $this->encodeJwt($payloadRefreshToken, $claveSecreta);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }

    private function encodeJwt(array $payload, string $secret): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), $secret, true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
