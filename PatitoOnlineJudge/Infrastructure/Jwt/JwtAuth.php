<?php

class JwtAuth
{
    function generateTokens($userId, array $userRoles)
    {
        $actualTime = time();
        $jwtTimeLife = isset($_SERVER["JWT_TIME_LIFE"]) ? (int) $_SERVER["JWT_TIME_LIFE"] : 1;
        $jwtTimeLife = $jwtTimeLife > 0 ? $jwtTimeLife : 1;

        $expirationTime = $actualTime + (60 * 60 * $jwtTimeLife); //1h default
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

        $jwtSecret = $_SERVER["JWT_SECRET_KEY"];
        $accessToken = $this->encodeJwt($payloadAccessToken, $jwtSecret);

        $payloadRefreshToken = [
            "sub"     => $userId,
            "roles"   => implode(",", array_column($userRoles, 'role_name')),
            "iat"     => $actualTime,
            "exp"     => $expirationTimeRefresh,
            "iss"     => $_SERVER["JWT_ISS"],
            "aud"     => $_SERVER["JWT_AUD"],
            "site_id" => $_SERVER["SITE_ID"]
        ];

        $refreshToken = $this->encodeJwt($payloadRefreshToken, $jwtSecret);

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
