<?php

namespace PatitoOnlineJudge\Repository;

use PatitoOnlineJudge\Config\DatabaseConnector;

class NewsRepository
{
    public function getLatestNews()
    {
        $connector = new DatabaseConnector();
        $pdo = $connector->getConnection();
        $sql = "SELECT * FROM `news` WHERE `defunct`!='Y' ORDER BY `importance` ASC,`time` DESC LIMIT 1";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }
}
