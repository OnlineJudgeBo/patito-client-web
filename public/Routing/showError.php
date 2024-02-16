<?php

use PatitoOnlineJudge\Presentation\Controller\ResultAnswerController;

require_once __DIR__ . '/container.php';

$resultAnswertController = $container->get(ResultAnswerController::class);

if (isset($_GET["sid"])) {
    $resultAnswertController->addSolutionId(intval($_GET["sid"]));
}
$resultAnswertController->render();
