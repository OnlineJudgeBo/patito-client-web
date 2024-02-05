<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ContestRankService;
use PatitoOnlineJudge\Core\Application\Services\ContestService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ContestRankRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ContestRepository;
use PatitoOnlineJudge\Presentation\Controller\ContestRankController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();

$contestRankRepository = new ContestRankRepository($connector);
$contestRankService = new ContestRankService($contestRankRepository);

$contestRepository = new ContestRepository($connector);
$contestService = new ContestService($contestRepository);


if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = new ContestRankController($contestRankService, $contestService);
    $constListProblemController->addCid($cid);
    $constListProblemController->render();
}
