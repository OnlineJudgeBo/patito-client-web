<?php

use PatitoOnlineJudge\Presentation\Controller\UserInfoController;

require_once __DIR__ . '/container.php';

$userInfo = $container->get(UserInfoController::class);
$userInfo->addUserId($_SESSION["user_id"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInfo->updateUserProfile($_REQUEST);
}

$userInfo->render();
