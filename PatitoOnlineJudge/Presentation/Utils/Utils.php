<?php

namespace PatitoOnlineJudge\Presentation\Utils;

class Utils
{
    public static function get_current_theme() {
        return __DIR__."/../Views/".$_SERVER["THEME_TEMPLATE"];
    }

    // Absolute URL of the site, e.g. https://jv.umsa.bo/oj (robots.txt, the sitemap and canonical links need it).
    public static function siteBaseUrl() {
        $https = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        $prefix = rtrim($_SERVER['APP_PREFIX_ROUTE'] ?? $_ENV['APP_PREFIX_ROUTE'] ?? '', '/');
        return ($https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $prefix;
    }
}
