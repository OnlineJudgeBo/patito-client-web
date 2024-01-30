<?php

namespace PatitoOnlineJudge\Infraestructure\Database\Implementations;

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\INewsRepository;
use PDO;

class NewsRepository implements INewsRepository
{
    public function getLatestNews()
    {
        $connector = new DatabaseConnector();
        $pdo = $connector->getConnection();
        $sql = "SELECT * FROM `news` WHERE `defunct`!='Y' ORDER BY `importance` ASC,`time` DESC LIMIT 1";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
