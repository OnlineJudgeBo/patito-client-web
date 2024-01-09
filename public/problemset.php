<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ProblemSetController;
use PatitoOnlineJudge\Repository\ProblemRepository;
use PatitoOnlineJudge\Service\ProblemService;

@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$connector = new DatabaseConnector();
$problemRepository = new ProblemRepository($connector);
$problemService = new ProblemService($problemRepository);

if (!isset($_GET["api"])) {
    $problemSetController = new ProblemSetController($problemService);
    $problemSetController->render();
} else {
    echo json_encode(array("data" => $problemService->getProblems(0, 100000)));
}
