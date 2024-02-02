<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\StatusService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\StatusRepository;
use PatitoOnlineJudge\Presentation\Controller\StatusController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$statusRepository = new StatusRepository($connector);
$statusService = new StatusService($statusRepository);

$statusController = new StatusController($statusService);

if (isset($_GET["cid"])) {
    $statusController->add_params("contest_id", $_GET["cid"]);
}
$statusController->render();
