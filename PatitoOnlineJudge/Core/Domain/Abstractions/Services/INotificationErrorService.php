<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface INotificationErrorService
{

    public function notifyError($error);
}
