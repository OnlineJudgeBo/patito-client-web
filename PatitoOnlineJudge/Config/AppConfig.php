<?php

namespace PatitoOnlineJudge\Config;

class AppConfig
{
    public static $DB_HOST = "patito-db";
    public static $DB_NAME = "patito-db-name";
    public static $DB_USER = "patito-root";
    public static $DB_PASS = "patito-root";

    public static function loadFromEnvironment()
    {
        self::$DB_HOST = self::env("DB_HOST", self::$DB_HOST);
        self::$DB_NAME = self::env("DB_NAME", self::$DB_NAME);
        self::$DB_USER = self::env("DB_USER", self::$DB_USER);
        self::$DB_PASS = self::env("DB_PASS", self::$DB_PASS);
    }

    private static function env($key, $default)
    {
        return $_SERVER[$key] ?? $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
