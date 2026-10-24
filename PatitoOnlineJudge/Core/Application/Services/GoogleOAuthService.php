<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Application\Exceptions\ApplicationException;

// "Sign in with Google" (OAuth 2.0 authorization code flow). Off until GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET are set.
class GoogleOAuthService
{
    private const AUTH_URL = "https://accounts.google.com/o/oauth2/v2/auth";
    private const TOKEN_URL = "https://oauth2.googleapis.com/token";

    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->clientId = $_SERVER["GOOGLE_CLIENT_ID"] ?? $_ENV["GOOGLE_CLIENT_ID"] ?? "";
        $this->clientSecret = $_SERVER["GOOGLE_CLIENT_SECRET"] ?? $_ENV["GOOGLE_CLIENT_SECRET"] ?? "";
    }

    public function isEnabled(): bool
    {
        return $this->clientId !== "" && $this->clientSecret !== "";
    }

    public function authorizationUrl(string $state, string $redirectUri): string
    {
        return self::AUTH_URL . "?" . http_build_query([
            "client_id" => $this->clientId,
            "redirect_uri" => $redirectUri,
            "response_type" => "code",
            "scope" => "openid email profile",
            "state" => $state,
            "prompt" => "select_account",
        ]);
    }

    // Trades the code for the user's verified email and name.
    public function fetchProfile(string $code, string $redirectUri): array
    {
        $response = @file_get_contents(self::TOKEN_URL, false, stream_context_create(["http" => [
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded",
            "content" => http_build_query([
                "code" => $code,
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret,
                "redirect_uri" => $redirectUri,
                "grant_type" => "authorization_code",
            ]),
            "timeout" => 10,
            "ignore_errors" => true,
        ]]));
        $idToken = json_decode($response ?: "", true)["id_token"] ?? "";
        $parts = explode(".", $idToken);
        if (count($parts) !== 3) {
            throw new ApplicationException("Google no respondió. Intenta de nuevo.");
        }

        // The token comes straight from Google over HTTPS, so its signature doesn't need checking here.
        $claims = json_decode(base64_decode(strtr($parts[1], "-_", "+/")), true) ?: [];
        $validIssuer = in_array($claims["iss"] ?? "", ["https://accounts.google.com", "accounts.google.com"], true);
        if (!$validIssuer || ($claims["aud"] ?? "") !== $this->clientId || ($claims["exp"] ?? 0) < time()) {
            throw new ApplicationException("La respuesta de Google no es válida. Intenta de nuevo.");
        }
        if (empty($claims["email"]) || ($claims["email_verified"] ?? false) !== true) {
            throw new ApplicationException("Tu cuenta de Google no tiene un correo verificado.");
        }

        return [
            "email" => strtolower($claims["email"]),
            "name" => $claims["given_name"] ?? $claims["name"] ?? "",
            "lastname" => $claims["family_name"] ?? "",
        ];
    }
}
