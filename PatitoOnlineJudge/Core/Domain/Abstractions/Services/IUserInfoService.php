<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IUserInfoService
{
    public function getSummarySolutions($userId, $type);
}
