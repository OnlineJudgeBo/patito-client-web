<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\LoginController;
use PatitoOnlineJudge\Repository\LoginRepository;
use PatitoOnlineJudge\Service\LoginService;

require_once __DIR__ . '/../../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository);
$loginController = new LoginController($loginService);

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $loginController->login($_POST["username"], $_POST["password"]);
}

$loginController->render();
