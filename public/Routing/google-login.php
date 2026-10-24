<?php

use PatitoOnlineJudge\Presentation\Controller\GoogleLoginController;

require_once __DIR__ . '/container.php';

$googleLoginController = $container->get(GoogleLoginController::class);

$page = basename(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
if ($page === "google-callback.php") {
    $googleLoginController->callback();
} elseif ($page === "google-register.php") {
    $googleLoginController->register();
} else {
    $googleLoginController->start();
}
