<?php

use PatitoOnlineJudge\Presentation\Controller\ContestController;
use PatitoOnlineJudge\Presentation\Controller\ContestListProblemController;

require_once __DIR__ . '/container.php';

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = $container->get(ContestListProblemController::class);
    $constListProblemController->addCid($cid);
    $constListProblemController->render();
} else {
    $contestController = $container->get(ContestController::class);
    $contestController->render();
}
