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

    private function base64UrlDecode(string $value): string
    {
        $padded = str_pad($value, strlen($value) % 4 === 0 ? strlen($value) : strlen($value) + (4 - strlen($value) % 4), '=');
        return base64_decode(strtr($padded, '-_', '+/'));
    }

    /**
     * Verifies the token signature and expiration, and returns its decoded payload.
     * @throws \Exception if the token is malformed, has an invalid signature, or is expired.
     */
    public function verifyToken(string $token): array
    {
        $segments = explode('.', $token);
        if (count($segments) !== 3) {
            throw new \Exception('Token inválido.');
        }
        [$headerSegment, $payloadSegment, $signatureSegment] = $segments;

        $jwtSecret = $_SERVER["JWT_SECRET_KEY"];
        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $headerSegment . '.' . $payloadSegment, $jwtSecret, true)
        );
        if (!hash_equals($expectedSignature, $signatureSegment)) {
            throw new \Exception('Firma de token inválida.');
        }

        $payload = json_decode($this->base64UrlDecode($payloadSegment), true);
        if (!is_array($payload) || !isset($payload['sub'], $payload['exp'])) {
            throw new \Exception('Token inválido.');
        }

        if (time() >= (int) $payload['exp']) {
            throw new \Exception('El token expiró.');
        }

        return $payload;
    }
}
