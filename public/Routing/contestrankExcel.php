<?php

use PatitoOnlineJudge\Presentation\Controller\ContestRankExcelController;

require_once __DIR__ . '/container.php';

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = $container->get(ContestRankExcelController::class);
    $constListProblemController->addCid($cid);
    $constListProblemController->render();
}
