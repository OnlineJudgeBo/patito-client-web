<?php

use PatitoOnlineJudge\Presentation\Controller\IcpcContestController;

require_once __DIR__ . '/container.php';

if (isset($_GET["cid"])) {
    $cid = $_GET["cid"];
    $constListProblemController = $container->get(IcpcContestController::class);
    $constListProblemController->addCid($cid);
    $constListProblemController->render();
} else {
    $contestController = $container->get(IcpcContestController::class);
    $contestController->render();
}
