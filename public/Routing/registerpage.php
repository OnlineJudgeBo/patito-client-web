<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Infraestructure\Presentation\DataObjectTransfer\UserDataObjectTransfer;
use PatitoOnlineJudge\Presentation\Controller\RegisterController;

require_once __DIR__ . '/../../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);

$userValidator = new UserValidator($loginRepository);

$loginService = new LoginService($loginRepository, $userValidator);
$registerController = new RegisterController($loginService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registerController->userRegister($_POST);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $registerController->render();
}
