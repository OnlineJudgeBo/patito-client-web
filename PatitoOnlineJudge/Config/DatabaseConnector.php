<?php

namespace PatitoOnlineJudge\Config;

use PatitoOnlineJudge\Config\AppConfig;
use PDO;

class DatabaseConnector
{
    private $judgeDsn;
    private $scheduleDsn;
    private $username;
    private $password;
    private $options;

    public function __construct()
    {
        date_default_timezone_set("America/La_Paz");
        AppConfig::loadFromEnvironment();

        $this->judgeDsn = "mysql:host=" . AppConfig::$DB_HOST . ";dbname=" . AppConfig::$DB_NAME . ";charset=utf8";
        $this->scheduleDsn = "mysql:host=" . AppConfig::$DB_HOST . ";dbname=schedule_management;charset=utf8";
        $this->username = AppConfig::$DB_USER;
        $this->password = AppConfig::$DB_PASS;

        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    public function getConnection()
    {
        try {
            return new PDO($this->judgeDsn, $this->username, $this->password, $this->options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getScheduleConnection()
    {
        try {
            return new PDO($this->scheduleDsn, $this->username, $this->password, $this->options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
