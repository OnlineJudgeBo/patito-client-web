<?php

use PatitoOnlineJudge\Presentation\Controller\RegisterController;

require_once __DIR__ . '/container.php';

$registerController = $container->get(RegisterController::class);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registerController->userRegister($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $registerController->render();
}
