<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Services\SubmitPageService;
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SourceCodeRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SubmitPageRepository;
use PatitoOnlineJudge\Presentation\Controller\SubmitPageController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();
$userValidator = new UserValidator($loginRepository);

$loginRepository = new LoginRepository($connector);
$loginService = new LoginService($loginRepository, $userValidator);

$submitPageRepository = new SubmitPageRepository($connector);
$sourceCodeRepository = new SourceCodeRepository($connector);

$submitPageService = new SubmitPageService($submitPageRepository, $sourceCodeRepository);
$submitPageController = new SubmitPageController($submitPageService, $loginService);


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET["id"]) && intval($_GET["id"]) > 0) {
        $submitPageController->addPid(intval($_GET["id"]));
    }
    if (!empty($_GET["cid"]) && intval($_GET["cid"]) > 0) {
        $submitPageController->addCid(intval($_GET["cid"]));
    }
    $submitPageController->render();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST["pid"]) && intval($_POST["pid"]) > 0) {
        $submitPageController->addPid(intval($_POST["pid"]));
    }
    if (!empty($_POST["cid"]) && intval($_POST["cid"]) > 0) {
        $submitPageController->addCid(intval($_POST["cid"]));
    }
    $submitPageController->addSource($_POST["source"]);
    $submitPageController->addLanguage($_POST["language_id"]);
    $submitPageController->saveRequest();
}
