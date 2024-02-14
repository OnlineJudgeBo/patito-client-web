<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\ContestService;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Services\SubmitPageService;
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ContestRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SourceCodeRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SubmitPageRepository;
use PatitoOnlineJudge\Presentation\Controller\SubmitPageController;

require_once __DIR__ . '/../../vendor/autoload.php';
$connector = new DatabaseConnector();

$loginRepository = new LoginRepository($connector);
$userValidator = new UserValidator($loginRepository);

$loginService = new LoginService($loginRepository, $userValidator);

$submitPageRepository = new SubmitPageRepository($connector);
$sourceCodeRepository = new SourceCodeRepository($connector);

$contestRepository = new ContestRepository($connector);
$contestService = new ContestService($contestRepository);

$submitPageService = new SubmitPageService($submitPageRepository, $sourceCodeRepository, $contestService);
$submitPageController = new SubmitPageController($submitPageService, $loginService);

$submitPageController->addContestService($contestService);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET["id"]) && intval($_GET["id"]) > 0) {
        $submitPageController->addPid(intval($_GET["id"]));
    }
    if (!empty($_GET["cid"]) && intval($_GET["cid"]) > 0) {
        $submitPageController->addCid(intval($_GET["cid"]));
        $submitPageController->addPid(intval($_GET["pid"]));
    }
    $submitPageController->render();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["pid"])) {
        $submitPageController->addPid(intval($_POST["pid"]));
    }

    if (isset($_POST["cid"])) {
        $submitPageController->addCid(intval($_POST["cid"]));
    }
    $submitPageController->addSource($_POST["source"]);
    $submitPageController->addLanguage($_POST["language_id"]);
    $submitPageController->saveRequest();
}
