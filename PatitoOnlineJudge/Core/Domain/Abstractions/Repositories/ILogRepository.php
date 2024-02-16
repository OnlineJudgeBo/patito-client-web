<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface ILogRepository
{
    public function addRecordHistory($hash, $userId, $ip, $ua, $uri, $refer);

}
