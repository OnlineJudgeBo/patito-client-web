<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ProblemService;
use PatitoOnlineJudge\Core\Application\Services\ProblemStatusService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemStatusRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\UserStaticRepository;
use PatitoOnlineJudge\Presentation\Controller\ProblemStatusController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$problemStatusRepository = new ProblemStatusRepository($connector);
$userStaticRepository = new UserStaticRepository($connector);
$problemRepository = new ProblemRepository($connector);


$problemStatusService = new ProblemStatusService($problemStatusRepository, $userStaticRepository);
$problemService = new ProblemService($problemRepository);


$problemStatusController = new ProblemStatusController($problemStatusService, $problemService);

if (isset($_GET["id"]) && intval($_GET["id"]) > 0) {
    $problemStatusController->addProblemId($_GET["id"]);
}

$problemStatusController->render();
