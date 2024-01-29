<?php

use PatitoOnlineJudge\Middleware\AuthMiddleware;

require __DIR__ . '/Router.php';
require_once __DIR__ . '/../vendor/autoload.php';


session_start();
ini_set("display_errors", "ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$router = new Router();
$authMiddleware = new AuthMiddleware();

$router->get('/index.php', function () {
    require  __DIR__ . '/routers/index.php';
});

$router->get('/', function () {
    require  __DIR__ . '/routers/index.php';
});

$router->get('/contest.php', function () {
    require  __DIR__ . '/routers/contest.php';
});

$router->get('/login.php', function () {
    require  __DIR__ . '/routers/login.php';
});

$router->post('/login.php', function () {
    require  __DIR__ . '/routers/login.php';
});

$router->get('/logout.php', function () {
    require  __DIR__ . '/routers/logout.php';
});

$router->get('/problem.php', function () {
    require  __DIR__ . '/routers/problem.php';
});

$router->get('/problemset.php', function () {
    require  __DIR__ . '/routers/problemset.php';
});

$router->get('/ranklist.php', function () {
    require  __DIR__ . '/routers/ranklist.php';
});

$router->get('/status.php', function () {
    require  __DIR__ . '/routers/status.php';
});

$router->group('/submitpage.php', function ($router) use ($authMiddleware) {
    $router->get('', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/routers/submitpage.php';
    });

    $router->post('', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/routers/submitpage.php';
    });
});


$router->dispatch();
