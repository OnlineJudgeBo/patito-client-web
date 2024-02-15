<?php

use PatitoOnlineJudge\Presentation\Controller\ProblemStatusController;

require_once __DIR__ . '/container.php';

$problemStatusController = $container->get(ProblemStatusController::class);

if (isset($_GET["id"]) && intval($_GET["id"]) > 0) {
    $problemStatusController->addProblemId($_GET["id"]);
}

$problemStatusController->render();
