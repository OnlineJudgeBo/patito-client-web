<?php
@session_start();
ini_set("display_errors","ON");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ .'/Legacy/Include/const.inc.php';

use PatitoOnlineJudge\Controller\IndexController;

$indexController = new IndexController();
$indexController->render();
