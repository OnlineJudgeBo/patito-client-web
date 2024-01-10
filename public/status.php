<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\StatusController;
use PatitoOnlineJudge\Repository\StatusRepository;
use PatitoOnlineJudge\Service\StatusService;

@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$connector = new DatabaseConnector();
$statusRepository = new StatusRepository($connector);
$statusService = new StatusService($statusRepository);

$statusController = new StatusController($statusService);
$statusController->render();
