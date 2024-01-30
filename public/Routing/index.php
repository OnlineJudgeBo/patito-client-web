<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\NewsService;
use PatitoOnlineJudge\Core\Application\Services\SolutionService;
use PatitoOnlineJudge\Core\Application\Services\StatusService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\NewsRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\SolutionRepository;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\StatusRepository;
use PatitoOnlineJudge\Presentation\Controller\IndexController;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../Legacy/Include/const.inc.php';

$connector = new DatabaseConnector();
$databaseConnection = $connector->getConnection();

$statusRepository = new StatusRepository($connector);
$statusService = new StatusService($statusRepository);

$newsService = new NewsService(new NewsRepository());
$solutionService = new SolutionService(new SolutionRepository());

$indexController = new IndexController($newsService, $solutionService, $statusService);


$indexController->render();
