<?php

use PatitoOnlineJudge\Presentation\Controller\StatusController;

require_once __DIR__ . '/container.php';

$statusController = $container->get(StatusController::class);

if (isset($_GET["cid"])) {
    $statusController->add_params("contest_id", $_GET["cid"]);
}
$statusController->render();
