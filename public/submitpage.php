<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\ContestController;
use PatitoOnlineJudge\Controller\SubmitPageController;
use PatitoOnlineJudge\Repository\SubmitPageRepository;
use PatitoOnlineJudge\Service\SubmitPageService;

session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
$connector = new DatabaseConnector();
$submitPageRepository = new SubmitPageRepository($connector);
$submitPageService = new SubmitPageService($submitPageRepository);

if (isset($_GET["id"])) {
    $cid = $_GET["id"];
    $constListProblemController = new SubmitPageController($submitPageService, $cid);
    $constListProblemController->render();
}

