<?php

use PatitoOnlineJudge\Core\Application\Services\RankListService;
use PatitoOnlineJudge\Presentation\Controller\RankListController;

require_once __DIR__ . '/container.php';

$rankListService = $container->get(RankListService::class);

if (isset($_GET["scope"])) {
} elseif (isset($_GET["api"])) {
    $realm = $_SERVER["REALM"];
    echo json_encode(array("data" => $rankListService->getRankListByDate("all", 0, $realm)));
} else {
    $problemSetController = $container->get(RankListController::class);
    $problemSetController->setRank(0);
    $problemSetController->setScope("all");
    $problemSetController->render();
}
