<?php

use PatitoOnlineJudge\Core\Application\Services\ShowSourceService;
use PatitoOnlineJudge\Presentation\Controller\DiffCodeController;

require_once __DIR__ . '/container.php';
$sourceCodeService = $container->get(ShowSourceService::class);
$faqController = $container->get(DiffCodeController::class);
$faqController->addService($sourceCodeService);
$faqController->setSolution1($_GET["solution_id"]);
$faqController->setSolution2($_GET["solution_id2"]);
$faqController->render();
