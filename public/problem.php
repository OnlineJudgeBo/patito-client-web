<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ContestController;
use PatitoOnlineJudge\Controller\ContestListProblemController;
use PatitoOnlineJudge\Controller\ProblemController;
use PatitoOnlineJudge\Repository\ContestRepository;
use PatitoOnlineJudge\Repository\ProblemRepository;
use PatitoOnlineJudge\Service\ContestService;
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

if (isset($_GET["cid"]) && isset($_GET["pid"])) {
    $cid = $_GET["cid"];
    $pid = $_GET["pid"];
    $problemController = new ProblemController($problemService);
    $problemController->setProblemId($pid);
    $problemController->setContestId($cid);
    $problemController->render();
}
