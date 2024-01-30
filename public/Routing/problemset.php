<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ProblemService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemRepository;
use PatitoOnlineJudge\Presentation\Controller\ProblemSetController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$problemRepository = new ProblemRepository($connector);
$problemService = new ProblemService($problemRepository);

if (!isset($_GET["api"])) {
    $problemSetController = new ProblemSetController($problemService);
    $problemSetController->render();
} else {
    echo json_encode(array("data" => $problemService->getProblems(0, 100000)));
}
