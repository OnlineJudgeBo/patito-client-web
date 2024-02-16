<?php

use PatitoOnlineJudge\Presentation\Controller\LoginController;

require_once __DIR__ . '/container.php';

$loginController = $container->get(LoginController::class);

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $loginController->login($_POST["username"], $_POST["password"]);
}

$loginController->render();
