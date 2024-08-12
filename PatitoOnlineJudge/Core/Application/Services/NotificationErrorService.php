<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INotificationErrorService;
use PatitoOnlineJudge\Infrastructure\Logger\Logger;

class NotificationErrorService implements INotificationErrorService
{
    private $loggerNotification;

    public function __construct()
    {
        $this->loggerNotification = new Logger();
    }

    public function notifyError($error)
    {
        $this->loggerNotification->notifyError($error);
    }
}
