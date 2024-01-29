<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Controller\IndexController;
use PatitoOnlineJudge\Repository\NewsRepository;
use PatitoOnlineJudge\Repository\SolutionRepository;
use PatitoOnlineJudge\Service\NewsService;
use PatitoOnlineJudge\Service\SolutionService;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../Legacy/Include/const.inc.php';

$connector = new DatabaseConnector();
$router = new Router();

$router->get('/', function () {
    $newsService = new NewsService(new NewsRepository());
    $solutionService = new SolutionService(new SolutionRepository());
    $indexController = new IndexController($newsService, $solutionService);
    $indexController->render();
});


$router->dispatch();
