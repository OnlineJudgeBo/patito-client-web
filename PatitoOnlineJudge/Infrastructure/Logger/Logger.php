<?php

namespace PatitoOnlineJudge\Infrastructure\Logger;

class Logger
{

    public function notifyError($e)
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
        @file_get_contents($url);

        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode("\nBacktrace Files:\n" . $backtrace);
        @file_get_contents($url);
    }
}
