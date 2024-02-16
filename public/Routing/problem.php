<?php

use PatitoOnlineJudge\Presentation\Controller\ProblemController;

require_once __DIR__ . '/container.php';

$problemController = $container->get(ProblemController::class);

if (isset($_GET["cid"]) && isset($_GET["pid"])) {
    $cid = $_GET["cid"];
    $pid = $_GET["pid"];
    $problemController->setProblemId($pid);
    $problemController->setContestId($cid);
} elseif (isset($_GET["id"])) {
    $pid = $_GET["id"];
    $problemController->setProblemId($pid);
}
$problemController->render();
