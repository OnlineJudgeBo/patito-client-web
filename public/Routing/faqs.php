<?php

use PatitoOnlineJudge\Presentation\Controller\FaqController;

require_once __DIR__ . '/../../vendor/autoload.php';
$faqController = new FaqController();
$faqController->render();
