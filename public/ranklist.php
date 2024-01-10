<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ProblemSetController;
use PatitoOnlineJudge\Controller\RankListController;
use PatitoOnlineJudge\Repository\ProblemRepository;
use PatitoOnlineJudge\Repository\RankListRepository;
use PatitoOnlineJudge\Service\ProblemService;
use PatitoOnlineJudge\Service\RankListService;

@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
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
