<?php

use Dotenv\Dotenv;
use PatitoOnlineJudge\Config\DatabaseConnector;
use PatitoOnlineJudge\Core\Application\Services\LogService;
use PatitoOnlineJudge\Infrastructure\Database\Implementations\LogRepository;
use PatitoOnlineJudge\Presentation\Middleware\AuthMiddleware;

require __DIR__ . '/Routing/Router.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
$envPath = __DIR__."/..";
if (file_exists($envPath . '/.env.local')) {
    $dotenv = Dotenv::createImmutable($envPath, '.env.local');
} else {
    $dotenv = Dotenv::createImmutable($envPath, '.env');
}
$dotenv->load();

$environment = $_SERVER['APP_ENV'] ?: 'production';
redirect();
if ($environment !== 'development') {
    try {
        executeRouter();
    } catch (\Throwable $e) {
        handleException($e);
    }
} else {
    ini_set("display_errors", "ON");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    executeRouter();
}

function redirect() 
{
// Obtener la URL solicitada
$requestUri = $_SERVER['REQUEST_URI'];

// Reemplazar "/oj" por "/"
$newUri = str_replace('/oj', '', $requestUri);

// Eliminar cualquier doble "//" en la URL
$newUri = preg_replace('#/+#', '/', $newUri); // Reemplaza múltiples "/" por un único "/"

// Asegurar que la URL comienza con "/"
if ($newUri[0] !== '/') {
    $newUri = '/' . $newUri;
}

// Redirigir si hubo un cambio
if ($requestUri !== $newUri) {
    // Construir la nueva URL completa
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $redirectUrl = $protocol . $host . $newUri;

    // Redirigir al usuario
    header("Location: $redirectUrl", true, 301);
    exit;
}

}

function executeRouter()
{
    global $prefix, $router, $authMiddleware;
    $router = new Router();
    $authMiddleware = new AuthMiddleware();
    $prefix = $_SERVER['APP_PREFIX_ROUTE'];

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

    $router->get('/oj/logout.php', function () {
        require  __DIR__ . '/Routing/logout.php';
    });

    $router->get($prefix . '/problem.php', function () {
        require  __DIR__ . '/Routing/problem.php';
    });

    $router->get($prefix . '/vibe-ide-launch.php', function () use ($authMiddleware) {
        $authMiddleware->handle();
        require  __DIR__ . '/Routing/vibe-ide-launch.php';
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

    $router->get($prefix . '/contestrankExcel.php', function () {
        require  __DIR__ . '/Routing/contestrankExcel.php';
    });

    $router->get($prefix . '/contestrank.php', function () {
        require  __DIR__ . '/Routing/contestrank.php';
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

    $router->post($prefix . '/updatepassword.php', function () {
        require  __DIR__ . '/Routing/updatepassword.php';
    });

    $router->get($prefix . '/showError.php', function () {
        require  __DIR__ . '/Routing/showError.php';
    });

    $router->get($prefix . '/faqs.php', function () {
        require  __DIR__ . '/Routing/faqs.php';
    });

    $router->get($prefix . '/userInfo.php', function () {
        require  __DIR__ . '/Routing/userInfo.php';
    });

    $router->post($prefix . '/userInfo.php', function () {
        require  __DIR__ . '/Routing/userInfo.php';
    });

    $router->get($prefix . '/spi.php', function () {
        require  __DIR__ . '/Routing/spi.php';
    });

    $router->get($prefix . '/redirect.php', function () {
        require  __DIR__ . '/Routing/redirect.php';
    });

    $router->get('/oj/redirect.php', function () {
        require  __DIR__ . '/Routing/redirect.php';
    });


    $router->get($prefix . '/diff_code.php', function () {
        require  __DIR__ . '/Routing/diffCode.php';
    });

    $router->get($prefix . '/icpc_contest.php', function () {
        require  __DIR__ . '/Routing/icpc_contest.php';
    });

    $router->dispatch();
}

function handleException(\Throwable $e)
{
    $error = error_get_last();
    if ($error) {
        ob_start();
        echo "Error Type: " . $error['type'] . "\n";
        echo "Error Message: " . $error['message'] . "\n";
        echo "Error File: " . $error['file'] . "\n";
        echo "Error Line: " . $error['line'] . "\n";
        $errorString = ob_get_clean();
    } else {
        $errorString = "No hay errores.";
    }

    ob_start();
    print_r(json_encode($_REQUEST, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    $requestDataString = ob_get_clean();

    ob_start();
    print_r($e);
    $backtrace = ob_get_clean();

    ob_start();
    debug_print_backtrace();
    $backtraceString = ob_get_clean();

    ob_start();
    print_r(json_encode($_SESSION, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    $sessionDataString = ob_get_clean();

    $urlActual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $metodoHttp = $_SERVER['REQUEST_METHOD'];
    $ipCliente = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $requestHttp = http_build_query($_REQUEST);

    $messageToSend = "Error: " . $errorString .
        "\Request:\n" . $requestHttp .
        "\nBacktrace:\n" . $backtraceString .
        "\nSession Data:\n" . $sessionDataString .
        "\nRequest Data:\n" . $requestDataString .
        "\nURL Actual: " . $urlActual .
        "\nMétodo HTTP: " . $metodoHttp .
        "\nIP Cliente: " . $ipCliente .
        "\nUser Agent: " . $userAgent .
        "\nMessage:\n" . (isset($e) ? $e->getMessage() : "No Exception Message");

    $botToken = $_SERVER['TELEGRAM_BOT_TOKEN'];
    $chatId = $_SERVER['TELEGRAM_CHAT_ID'];;

    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($messageToSend);
    file_get_contents($url);

    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode("\nBacktrace Files:\n" . $backtrace);
    file_get_contents($url);

    echo "<pre>";
    print_r("Disculpe, hemos detectado un error interno. Por favor, regrese a la pantalla anterior.<br>Lo solucionaremos pronto, agradecemos su comprensión y paciencia. Gracias.");
    echo "</pre>";
    exit();
}
