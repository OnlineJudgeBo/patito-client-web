<?php

use PatitoOnlineJudge\Core\Application\Services\RankListService;
use PatitoOnlineJudge\Presentation\Controller\RankListController;

require_once __DIR__ . '/container.php';

$rankListService = $container->get(RankListService::class);
$scope = $_GET["scope"] ?? "all";
$allowedScopes = ["all", "m", "w", "d"];

if (!in_array($scope, $allowedScopes, true)) {
    $scope = "all";
}

if (isset($_GET["api"])) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(array("data" => $rankListService->getRankListByDate($scope, 0)));
} else {
    $problemSetController = $container->get(RankListController::class);
    $problemSetController->setRank(0);
    $problemSetController->setScope($scope);
    $problemSetController->render();
}
