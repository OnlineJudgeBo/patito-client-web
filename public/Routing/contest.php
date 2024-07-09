<?php

use PatitoOnlineJudge\Presentation\Controller\ContestController;
use PatitoOnlineJudge\Presentation\Controller\ContestListProblemController;

require_once __DIR__ . '/container.php';

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    if (isset($_GET["type"])) {
        $ctype = $_GET["type"];
    } else {
        $ctype = "";
    }
    $constListProblemController = $container->get(ContestListProblemController::class);
    $constListProblemController->addCid($cid);
    $constListProblemController->addCtype($ctype);
    $constListProblemController->render();
} else {
    $contestController = $container->get(ContestController::class);
    $contestController->render();
}
