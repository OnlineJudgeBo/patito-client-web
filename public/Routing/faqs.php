<?php

use PatitoOnlineJudge\Presentation\Controller\FaqController;

require_once __DIR__ . '/container.php';

$faqController = $container->get(FaqController::class);
$faqController->render();
