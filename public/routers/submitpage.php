<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\SubmitPageController;
use PatitoOnlineJudge\Repository\LoginRepository;
use PatitoOnlineJudge\Repository\SubmitPageRepository;
use PatitoOnlineJudge\Service\LoginService;
use PatitoOnlineJudge\Service\SubmitPageService;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();

$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository);

$submitPageRepository = new SubmitPageRepository($connector);
$submitPageService = new SubmitPageService($submitPageRepository);

if (isset($_GET["id"]) || isset($_GET["cid"])) {
    if (empty($_GET["id"])) {
        $pid = 0;
    } else {
        $pid = intval($_GET["id"]);
    }

    if (empty($_GET["cid"])) {
        $cid = 0;
    } else {
        $cid = intval($_GET["cid"]);
    }


    $constListProblemController = new SubmitPageController($submitPageService, $loginService, $pid, $cid);
    $constListProblemController->render();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid = 0;
    $pid = 0;

    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    exit();
}
