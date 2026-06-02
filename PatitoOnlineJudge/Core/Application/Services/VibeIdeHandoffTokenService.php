<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use InvalidArgumentException;
use RuntimeException;

class VibeIdeHandoffTokenService
{
    private const DEFAULT_TTL_SECONDS = 7200;
    private const DEFAULT_ISSUER = 'patito-online-judge';
    private const DEFAULT_AUDIENCE = 'vibe-ide';

    public function createLaunchToken(array $claims, ?int $now = null): string
    {
        $now = $now ?? time();
        $payload = array_merge($claims, [
            'iss' => $this->env('VIBE_IDE_TOKEN_ISS', self::DEFAULT_ISSUER),
            'aud' => $this->env('VIBE_IDE_TOKEN_AUD', self::DEFAULT_AUDIENCE),
            'iat' => $now,
            'exp' => $now + $this->ttlSeconds(),
            'jti' => bin2hex(random_bytes(16)),
        ]);

        return $this->encode($payload);
    }

    public function verify(string $token, ?int $now = null): array
    {
        $now = $now ?? time();
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException('Invalid Vibe IDE handoff token format.');
        }

        [$encodedHeader, $encodedPayload, $signature] = $parts;
        $expectedSignature = $this->sign($encodedHeader . '.' . $encodedPayload);
        if (!hash_equals($expectedSignature, $signature)) {
            throw new InvalidArgumentException('Invalid Vibe IDE handoff token signature.');
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        if (!is_array($payload)) {
            throw new InvalidArgumentException('Invalid Vibe IDE handoff token payload.');
        }

        if (($payload['iss'] ?? null) !== $this->env('VIBE_IDE_TOKEN_ISS', self::DEFAULT_ISSUER)) {
            throw new InvalidArgumentException('Invalid Vibe IDE handoff token issuer.');
        }

        if (($payload['aud'] ?? null) !== $this->env('VIBE_IDE_TOKEN_AUD', self::DEFAULT_AUDIENCE)) {
            throw new InvalidArgumentException('Invalid Vibe IDE handoff token audience.');
        }

        if (!isset($payload['exp']) || intval($payload['exp']) < $now) {
            throw new InvalidArgumentException('Expired Vibe IDE handoff token.');
        }

        return $payload;
    }

    private function encode(array $payload): string
    {
        $encodedHeader = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256'], JSON_THROW_ON_ERROR));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        return $encodedHeader . '.' . $encodedPayload . '.' . $this->sign($encodedHeader . '.' . $encodedPayload);
    }

    private function sign(string $message): string
    {
        return $this->base64UrlEncode(hash_hmac('sha256', $message, $this->secret(), true));
    }

    private function secret(): string
    {
        $secret = $this->env('VIBE_IDE_TOKEN_SECRET', '');
        if (strlen($secret) < 32) {
            throw new RuntimeException('VIBE_IDE_TOKEN_SECRET must be configured with at least 32 characters.');
        }
        return $secret;
    }

    private function ttlSeconds(): int
    {
        $ttl = intval($this->env('VIBE_IDE_TOKEN_TTL_SECONDS', strval(self::DEFAULT_TTL_SECONDS)));
        return $ttl > 0 ? $ttl : self::DEFAULT_TTL_SECONDS;
    }

    private function env(string $key, string $default): string
    {
        return strval($_SERVER[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $padding = strlen($value) % 4;
        if ($padding > 0) {
            $value .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($value, '-_', '+/')) ?: '';
    }
}
