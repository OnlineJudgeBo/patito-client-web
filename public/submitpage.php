<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\SubmitPageController;
use PatitoOnlineJudge\Repository\LoginRepository;
use PatitoOnlineJudge\Repository\SubmitPageRepository;
use PatitoOnlineJudge\Service\LoginService;
use PatitoOnlineJudge\Service\SubmitPageService;

session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$connector = new DatabaseConnector();

$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository);

$submitPageRepository = new SubmitPageRepository($connector);
$submitPageService = new SubmitPageService($submitPageRepository);

if (isset($_GET["id"])) {
    $pid = $_GET["id"];
    $cid = $_GET["cid"];
    $constListProblemController = new SubmitPageController($submitPageService, $loginService, $pid, $cid);
    $constListProblemController->render();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    exit();
    //if (isset($_POST[""]))
}

