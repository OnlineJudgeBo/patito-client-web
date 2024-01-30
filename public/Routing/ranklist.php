<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\RankListService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\RankListRepository;
use PatitoOnlineJudge\Presentation\Controller\RankListController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$rankListRepository = new RankListRepository($connector);
$rankListService = new RankListService($rankListRepository);

if (isset($_GET["scope"])) {
} elseif (isset($_GET["api"])) {
    echo json_encode(array("data" => $rankListService->getRankListByDate("all", 0)));
} else {
    $problemSetController = new RankListController($rankListService);
    $problemSetController->setRank(0);
    $problemSetController->setScope("all");
    $problemSetController->render();
}
