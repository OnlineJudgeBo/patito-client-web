<?php

use PatitoOnlineJudge\Presentation\Controller\StatusController;

require_once __DIR__ . '/container.php';

$statusController = $container->get(StatusController::class);

if (isset($_GET["cid"])) {
    $statusController->add_params("contest_id", $_GET["cid"]);
}

if (isset($_GET["user_id"])) {
    $statusController->add_params("user_id", $_GET["user_id"]);
}

if (isset($_GET["problem_id"])) {
    $statusController->add_params("problem_id", $_GET["problem_id"]);
}

$statusController->render();
