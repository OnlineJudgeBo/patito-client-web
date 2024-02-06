<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ShowSourceService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ShowSourceRepository;
use PatitoOnlineJudge\Presentation\Controller\ShowSourceController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$showSourceRepository = new ShowSourceRepository($connector);
$showSourceService = new ShowSourceService($showSourceRepository);

$statusController = new ShowSourceController($showSourceService);

if (isset($_GET["id"])) {
    $statusController->addSolutionId($_GET["id"]);
}
$statusController->render();