<?php

namespace PatitoOnlineJudge\Presentation\Middleware;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;

// Rejects with an empty 403 the clients blocked in the admin panel. The rules are read from the
// block_rule table (managed by the admin API) and cached in a file for CACHE_SECONDS, so a change
// applies within that time. Same matching as the API's BlockRule.Blocks.
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
        $cache = sys_get_temp_dir() . '/patito-block-rules.json';
        if (!is_file($cache) || time() - filemtime($cache) >= self::CACHE_SECONDS) {
            try {
                $rows = (new DatabaseConnector())->getConnection()
                    ->query("SELECT type, value FROM block_rule")
                    ->fetchAll(PDO::FETCH_ASSOC);
                $tmp = $cache . '.' . getmypid();
                file_put_contents($tmp, json_encode($rows));
                rename($tmp, $cache); // atomic, other workers never read half a file
            } catch (\Throwable $e) {
                touch($cache); // table missing or database down: keep the last rules (or none) and retry later
            }
        }

        $rules = json_decode((string) @file_get_contents($cache), true);
        return is_array($rules) ? $rules : [];
    }
}
