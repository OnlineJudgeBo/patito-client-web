<?php

use PatitoOnlineJudge\Presentation\Controller\RecoveryPasswordController;

require_once __DIR__ . '/container.php';

$registerController = $container->get(RecoveryPasswordController::class);
$registerController->userRecoveryPassword($_POST);
