<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ContestController;
use PatitoOnlineJudge\Repository\ContestRepository;
use PatitoOnlineJudge\Service\ContestService;

@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$connector = new DatabaseConnector();
$contestRepository = new ContestRepository($connector);
$contestService = new ContestService($contestRepository);
$contestController = new ContestController($contestService);
$contestController->render();
