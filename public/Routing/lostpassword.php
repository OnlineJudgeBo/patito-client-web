<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Presentation\Controller\RecoveryPasswordController;

require_once __DIR__ . '/../../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);

$userValidator = new UserValidator($loginRepository);

$loginService = new LoginService($loginRepository, $userValidator);
$recoveryPasswordController = new RecoveryPasswordController($loginService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recoveryPasswordController->userRecoveryPassword($_POST);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $recoveryPasswordController->render();
}
