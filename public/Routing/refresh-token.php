<?php

use PatitoOnlineJudge\Presentation\Controller\LoginController;

require_once __DIR__ . '/container.php';

$loginController = $container->get(LoginController::class);
$loginController->refresh();
