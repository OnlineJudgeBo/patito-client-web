<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Services\SubmitPageService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SubmitPageRepository;
use PatitoOnlineJudge\Presentation\Controller\SubmitPageController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();

$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository);

$submitPageRepository = new SubmitPageRepository($connector);
$submitPageService = new SubmitPageService($submitPageRepository);
$submitPageController = new SubmitPageController($submitPageService, $loginService);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET["id"]) && intval($_GET["id"]) > 0) {
        $submitPageController->addPid(intval($_GET["id"]));
    }
    if (!empty($_GET["cid"]) && intval($_GET["cid"]) > 0) {
        $submitPageController->addCid(intval($_GET["cid"]));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_GET["id"]) && intval($_GET["id"]) > 0) {
        $submitPageController->addPid(intval($_GET["id"]));
    }
    if (!empty($_GET["cid"]) && intval($_GET["cid"]) > 0) {
        $submitPageController->addCid(intval($_GET["cid"]));
    }

    $submitPageController->addSource($_POST["source"]);
    $submitPageController->saveRequest();
}

$submitPageController->render();
