<?php

use PatitoOnlineJudge\Presentation\Controller\UpdatePasswordController;

require_once __DIR__ . '/container.php';

$updatePasswordController = $container->get(UpdatePasswordController::class);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $updatePasswordController->showPasswordPage($_SERVER['REQUEST_URI']);
        $updatePasswordController->render();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updatePasswordController->updatePasswordByToken($_POST);
        header("Location: ./login.php");
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
