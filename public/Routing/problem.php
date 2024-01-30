<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ProblemService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemRepository;
use PatitoOnlineJudge\Presentation\Controller\ProblemController;

require_once __DIR__ . '/../../vendor/autoload.php';
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
