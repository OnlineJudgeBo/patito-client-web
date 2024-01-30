<?php

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

$router->get($prefix. '/index.php', function () {
    require  __DIR__ . '/Routing/index.php';
});

$router->get($prefix. '/', function () {
    require  __DIR__ . '/Routing/index.php';
});

$router->get($prefix. '/contest.php', function () {
    require  __DIR__ . '/Routing/contest.php';
});

$router->get($prefix. '/login.php', function () {
    require  __DIR__ . '/Routing/login.php';
});

$router->post('/login.php', function () {
    require  __DIR__ . '/Routing/login.php';
});

$router->get($prefix. '/logout.php', function () {
    require  __DIR__ . '/Routing/logout.php';
});

$router->get($prefix. '/problem.php', function () {
    require  __DIR__ . '/Routing/problem.php';
});

$router->get($prefix. '/problemset.php', function () {
    require  __DIR__ . '/Routing/problemset.php';
});

$router->get($prefix. '/ranklist.php', function () {
    require  __DIR__ . '/Routing/ranklist.php';
});

$router->get($prefix. '/status.php', function () {
    require  __DIR__ . '/Routing/status.php';
});

$router->group($prefix. '/submitpage.php', function ($router, $prefix) use ($authMiddleware) {
    $router->get($prefix. '', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/Routing/submitpage.php';
    });

    $router->post($prefix. '', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/Routing/submitpage.php';
    });
});


$router->dispatch();
