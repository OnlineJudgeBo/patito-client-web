<?php

use PatitoOnlineJudge\Presentation\Controller\IndexController;

require_once __DIR__ . '/container.php';
require_once __DIR__ . '/../../Legacy/Include/const.inc.php';
$indexController = $container->get(IndexController::class);
$indexController->render();
