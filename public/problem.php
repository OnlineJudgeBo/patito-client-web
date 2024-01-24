<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ProblemController;
use PatitoOnlineJudge\Repository\ProblemRepository;
use PatitoOnlineJudge\Service\ProblemService;

session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$connector = new DatabaseConnector();
$problemRepository = new ProblemRepository($connector);
$problemService = new ProblemService($problemRepository);
$problemController = new ProblemController($problemService);

if (isset($_GET["cid"]) && isset($_GET["pid"])) {
    $cid = $_GET["cid"];
    $pid = $_GET["pid"];
    $problemController->setProblemId($pid);
    $problemController->setContestId($cid);
} elseif (isset($_GET["id"])) {
    $pid = $_GET["id"];
    $problemController->setProblemId($pid);
}
$problemController->render();
