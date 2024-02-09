<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LoginService;
use PatitoOnlineJudge\Core\Application\Validators\UserValidator;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LoginRepository;
use PatitoOnlineJudge\Presentation\Controller\UpdatePasswordController;

require_once __DIR__ . '/../../vendor/autoload.php';

$connector = new DatabaseConnector();
$loginRepository = new LoginRepository($connector);
$userValidator = new UserValidator($loginRepository);
$loginService = new LoginService($loginRepository, $userValidator);
$updatePasswordController = new UpdatePasswordController($loginService);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $updatePasswordController->showPasswordPage($_SERVER['REQUEST_URI']);
        $updatePasswordController->render();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        die("Ocurrio un error, por favor contacte al administrador. Gracias");
    }


} catch (\Exception $e) {
    echo $e->getMessage();
}
