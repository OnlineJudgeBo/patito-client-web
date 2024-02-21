<?php

use PatitoOnlineJudge\Core\Application\Services\ProblemService;
use PatitoOnlineJudge\Presentation\Controller\ProblemSetController;

require_once __DIR__ . '/container.php';

$problemService = $container->get(ProblemService::class);
if (isset($_GET["api"])) {
    $user_id = "";
    if (isset($_GET["user_id"])) {
        $user_id = $_GET["user_id"];
        $problemService->addUserid($user_id);
    }
    echo json_encode(array("data" => $problemService->getProblems(0, 100000)));
} else {
    $problemSetController = $container->get(ProblemSetController::class);
    $problemSetController->render();
}
