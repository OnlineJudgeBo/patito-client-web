<?php
@session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PatitoOnlineJudge\Controller\IndexController;
use PatitoOnlineJudge\Repository\NewsRepository;
use PatitoOnlineJudge\Repository\SolutionRepository;
use PatitoOnlineJudge\Service\NewsService;
use PatitoOnlineJudge\Service\SolutionService;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Legacy/Include/const.inc.php';

$newsService = new NewsService(new NewsRepository());
$solutionService = new SolutionService(new SolutionRepository());
$indexController = new IndexController($newsService, $solutionService);
$indexController->render();
