<?php
require_once __DIR__ . '/container.php';

use PatitoOnlineJudge\Core\Application\Services\ContestService;
use PatitoOnlineJudge\Presentation\Controller\SubmitPageController;

$submitPageController = $container->get(SubmitPageController::class);
$contestService = $container->get(ContestService::class);

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
