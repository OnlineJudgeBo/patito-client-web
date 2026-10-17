<?php

namespace PatitoOnlineJudge\Presentation\Middleware;

// Rejects with an empty 403 the clients blocked in the admin panel. The rules come from the admin API
// (BLOCK_RULES_URL, with BLOCK_RULES_TOKEN) and are cached in a file for CACHE_SECONDS; when the API
// does not answer, the last rules keep applying. Same matching as the API's BlockRule.Blocks.
class BlockRulesMiddleware
{
    private const CACHE_SECONDS = 30;

    public function handle()
    {
        $rules = $this->rules();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $text = ($_SERVER['HTTP_USER_AGENT'] ?? '') . ' ' . ($_SERVER['HTTP_REFERER'] ?? '') . ' ' . ($_SERVER['REQUEST_URI'] ?? '');

        if (self::blocks($rules, $ip, $text)) {
            http_response_code(403);
            exit;
        }
    }

    public static function blocks(array $rules, string $ip, string $text): bool
    {
        foreach ($rules as $rule) {
            $value = (string) ($rule['value'] ?? '');
            $matches = ($rule['type'] ?? '') === 'ip'
                ? self::ipMatches($ip, $value)
                : $value !== '' && stripos($text, $value) !== false;
            if ($matches) {
                return true;
            }
        }
        return false;
    }

    // An address or an IPv4 CIDR range such as 190.129.0.0/16.
    private static function ipMatches(string $ip, string $rule): bool
    {
        if ($rule === $ip) {
            return true;
        }
        if (strpos($rule, '/') === false) {
            return false;
        }
        [$network, $bits] = explode('/', $rule, 2);
        $address = ip2long($ip);
        $start = ip2long($network);
        $bits = (int) $bits;
        if ($address === false || $start === false || $bits < 0 || $bits > 32) {
            return false;
        }
        $mask = $bits === 0 ? 0 : (~0 << (32 - $bits)) & 0xFFFFFFFF;
        return ($address & $mask) === ($start & $mask);
    }

    private function rules(): array
    {
        $url = getenv('BLOCK_RULES_URL') ?: '';
        if ($url === '') {
            return [];
        }

        $cache = sys_get_temp_dir() . '/patito-block-rules.json';
        $fresh = is_file($cache) && time() - filemtime($cache) < self::CACHE_SECONDS;
        if (!$fresh) {
            $body = @file_get_contents($url, false, stream_context_create(['http' => [
                'timeout' => 1,
                'header' => 'X-Block-Rules-Token: ' . (getenv('BLOCK_RULES_TOKEN') ?: ''),
            ]]));
            if ($body !== false && is_array(json_decode($body, true))) {
                $tmp = $cache . '.' . getmypid();
                file_put_contents($tmp, $body);
                rename($tmp, $cache); // atomic, other workers never read half a file
            } else {
                touch($cache); // API down: keep the last rules (or none) and retry later, not on every request
            }
        }

        $rules = is_file($cache) ? json_decode((string) file_get_contents($cache), true) : null;
        return is_array($rules) ? $rules : [];
    }
}
