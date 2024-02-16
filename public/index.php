<?php

use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LogService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\LogRepository;
use PatitoOnlineJudge\Presentation\Middleware\AuthMiddleware;

require __DIR__ . '/Routing/Router.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$router = new Router();
$authMiddleware = new AuthMiddleware();
$prefix = "/oj";

$databaseConnector = new DatabaseConnector();
$logRepository = new LogRepository($databaseConnector);
$logService = new LogService($logRepository);
$logService->addRecordHistory();

$router->get($prefix . '/index.php', function () {
    require  __DIR__ . '/Routing/index.php';
});

$router->get($prefix . '/', function () {
    require  __DIR__ . '/Routing/index.php';
});

$router->get($prefix . '/contest.php', function () {
    require  __DIR__ . '/Routing/contest.php';
});

$router->get($prefix . '/login.php', function () {
    require  __DIR__ . '/Routing/login.php';
});

$router->post($prefix . '/login.php', function () {
    require  __DIR__ . '/Routing/login.php';
});

$router->get($prefix . '/logout.php', function () {
    require  __DIR__ . '/Routing/logout.php';
});

$router->get($prefix . '/problem.php', function () {
    require  __DIR__ . '/Routing/problem.php';
});

$router->get($prefix . '/problemset.php', function () {
    require  __DIR__ . '/Routing/problemset.php';
});

$router->get($prefix . '/ranklist.php', function () {
    require  __DIR__ . '/Routing/ranklist.php';
});

$router->get($prefix . '/status.php', function () {
    require  __DIR__ . '/Routing/status.php';
});

$router->get($prefix . '/problemstatus.php', function () {
    require  __DIR__ . '/Routing/problemstatus.php';
});

$router->group($prefix . '/submitpage.php', function ($router) use ($authMiddleware) {
    $router->get('', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/Routing/submitpage.php';
    });

    $router->post('', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/Routing/submitpage.php';
    });
});

$router->get($prefix . '/contestrank.php', function () {
    require  __DIR__ . '/Routing/contestrank.php';
});

$router->get($prefix . '/userinfo.php', function () {
    require  __DIR__ . '/Routing/userinfo.php';
});

$router->get($prefix . '/showsource.php', function () use ($authMiddleware) {
    $authMiddleware->handle();
    require  __DIR__ . '/Routing/showsource.php';
});

$router->get($prefix . '/registerpage.php', function () {
    require  __DIR__ . '/Routing/registerpage.php';
});

$router->post($prefix . '/registerpage.php', function () {
    require  __DIR__ . '/Routing/registerpage.php';
});

$router->get($prefix . '/lostpassword.php', function () {
    require  __DIR__ . '/Routing/lostpassword.php';
});

$router->post($prefix . '/lostpassword.php', function () {
    require  __DIR__ . '/Routing/lostpassword.php';
});

$router->get($prefix . '/recoverypassword.php', function () {
    require  __DIR__ . '/Routing/recoverypassword.php';
});

$router->get($prefix . '/updatepassword.php', function () {
    require  __DIR__ . '/Routing/updatepassword.php';
});

$router->get($prefix . '/faqs.php', function () {
    require  __DIR__ . '/Routing/faqs.php';
});

$router->dispatch();
