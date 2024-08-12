<?php

namespace PatitoOnlineJudge\Infrastructure\Database\Implementations;

use Exception;
use InvalidArgumentException;
use PatitoOnlineJudge\Config\DatabaseConnector;
use PDO;
use PDOException;

abstract class BaseRepository
{
    protected $pdo;

    public function __construct(DatabaseConnector $connector)
    {
        $this->pdo = $connector->getConnection();
    }

    protected function validateParams(array $params)
    {
        foreach ($params as $param) {
            if (!is_scalar($param) && !is_null($param)) {
                throw new InvalidArgumentException('Invalid parameter type.');
            }
        }
    }

    protected function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    protected function commit()
    {
        $this->pdo->commit();
    }

    protected function executeQuery($query, $params = [])
    {
        $this->validateParams($params);

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $sms = "Database error: " . $e->getMessage() . " Query: $query Params: " . json_encode($params);
            error_log($sms);
            throw new Exception($sms);
        }
    }

    protected function fetchAll($query, $params = [])
    {
        $this->validateParams($params);

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $sms = "Database error: " . $e->getMessage() . " Query: $query Params: " . json_encode($params);
            error_log($sms);
            throw new Exception($sms);
        }
    }

    protected function fetchColumn($query, $params = [])
    {
        $this->validateParams($params);

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            $sms = "Database error: " . $e->getMessage() . " Query: $query Params: " . json_encode($params);
            error_log($sms);
            throw new Exception($sms);
        }
    }
}
