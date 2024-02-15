<?php

use PatitoOnlineJudge\Presentation\Controller\ContestRankController;

require_once __DIR__ . '/container.php';

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = $container->get(ContestRankController::class);
    $constListProblemController->addCid($cid);
    $constListProblemController->render();
}
