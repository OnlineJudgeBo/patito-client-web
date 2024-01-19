<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\LoginController;
use PatitoOnlineJudge\Repository\LoginRepository;
use PatitoOnlineJudge\Service\LoginService;

@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository);
$loginController = new LoginController($loginService);

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $loginController->login($_POST["username"], $_POST["password"]);
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit();
} else {
    $loginController->render();
}
