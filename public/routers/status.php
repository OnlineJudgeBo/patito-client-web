<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\StatusController;
use PatitoOnlineJudge\Repository\StatusRepository;
use PatitoOnlineJudge\Service\StatusService;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$statusRepository = new StatusRepository($connector);
$statusService = new StatusService($statusRepository);

$statusController = new StatusController($statusService);
$statusController->render();
