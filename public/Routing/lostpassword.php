<?php

use PatitoOnlineJudge\Presentation\Controller\RecoveryPasswordController;

require_once __DIR__ . '/container.php';

$recoveryPasswordController = $container->get(RecoveryPasswordController::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recoveryPasswordController->userRecoveryPassword($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $recoveryPasswordController->render();
}
