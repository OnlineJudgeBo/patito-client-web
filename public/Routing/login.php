<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Presentation\Controller\LoginController;

require_once __DIR__ . '/../../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);

$loginService = new LoginService($loginRepository);
$loginController = new LoginController($loginService);

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $loginController->login($_POST["username"], $_POST["password"]);
}

$loginController->render();
