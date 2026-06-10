<?php

namespace PatitoOnlineJudge\Infrastructure\Logger;

class Logger
{
    public function notifyError(\Throwable $e): void
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

        $backtrace = (string) $e;
        $sessionDataString = json_encode(
            $_SESSION ?? [],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        $urlActual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://" . ($_SERVER['HTTP_HOST'] ?? 'unknown-host')
            . ($_SERVER['REQUEST_URI'] ?? '/');

        $metodoHttp = $_SERVER['REQUEST_METHOD'] ?? 'unknown';
        $ipCliente = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $requestHttp = http_build_query($_REQUEST);

        $messageToSend = "Error: " . $errorString .
            "\nRequest:\n" . $requestHttp .
            "\nSession Data:\n" . $sessionDataString .
            "\nRequest Data:\n" . $requestDataString .
            "\nURL Actual: " . $urlActual .
            "\nMétodo HTTP: " . $metodoHttp .
            "\nIP Cliente: " . $ipCliente .
            "\nUser Agent: " . $userAgent .
            "\nMessage:\n" . $e->getMessage();

        $this->sendTelegramMessage($messageToSend);
        $this->sendTelegramMessage("\nBacktrace Files:\n" . $backtrace);
    }

    private function sendTelegramMessage(string $message): void
    {
        $botToken = $_SERVER['TELEGRAM_BOT_TOKEN'] ?? $_ENV['TELEGRAM_BOT_TOKEN'] ?? '';
        $chatId = $_SERVER['TELEGRAM_CHAT_ID'] ?? $_ENV['TELEGRAM_CHAT_ID'] ?? '';

        if ($botToken === '' || $chatId === '') {
            return;
        }

        $payload = http_build_query([
            'chat_id' => $chatId,
            'text' => substr($message, 0, 4000),
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($payload) . "\r\n",
                'content' => $payload,
                'ignore_errors' => true,
                'timeout' => 3,
            ],
        ]);

        @file_get_contents(
            "https://api.telegram.org/bot" . $botToken . "/sendMessage",
            false,
            $context
        );
    }
}
