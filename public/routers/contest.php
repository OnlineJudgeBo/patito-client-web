<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ContestController;
use PatitoOnlineJudge\Controller\ContestListProblemController;
use PatitoOnlineJudge\Repository\ContestRepository;
use PatitoOnlineJudge\Service\ContestService;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$contestRepository = new ContestRepository($connector);
$contestService = new ContestService($contestRepository);

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = new ContestListProblemController($contestService, $cid);
    $constListProblemController->render();
} else {
    $contestController = new ContestController($contestService);
    $contestController->render();
}


