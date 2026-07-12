<?php

use PatitoOnlineJudge\Core\Application\Services\ShowSourceService;
use PatitoOnlineJudge\Presentation\Controller\DiffCodeController;

require_once __DIR__ . '/container.php';

$solutionId = filter_input(INPUT_GET, "solution_id", FILTER_VALIDATE_INT);
$solutionId2 = filter_input(INPUT_GET, "solution_id2", FILTER_VALIDATE_INT);

$sourceCodeService = $container->get(ShowSourceService::class);
$diffCodeController = $container->get(DiffCodeController::class);
$diffCodeController->addService($sourceCodeService);
$diffCodeController->setSolution1($solutionId ?: null);
$diffCodeController->setSolution2($solutionId2 ?: null);
$diffCodeController->render();
