<?php

use PatitoOnlineJudge\Presentation\Controller\ShowSourceController;

require_once __DIR__ . '/container.php';

$statusController = $container->get(ShowSourceController::class);

if (isset($_GET["id"])) {
    $statusController->addSolutionId($_GET["id"]);
}
$statusController->render();
