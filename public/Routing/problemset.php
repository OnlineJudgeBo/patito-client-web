<?php

use PatitoOnlineJudge\Core\Application\Services\ProblemService;
use PatitoOnlineJudge\Presentation\Controller\ProblemSetController;

require_once __DIR__ . '/container.php';

$problemService = $container->get(ProblemService::class);
if (!isset($_GET["api"])) {
    $problemSetController = $container->get(ProblemSetController::class);
    $problemSetController->render();
} else {
    echo json_encode(array("data" => $problemService->getProblems(0, 100000)));
}
