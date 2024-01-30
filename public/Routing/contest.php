<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ContestService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ContestRepository;
use PatitoOnlineJudge\Presentation\Controller\ContestController;
use PatitoOnlineJudge\Presentation\Controller\ContestListProblemController;

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


