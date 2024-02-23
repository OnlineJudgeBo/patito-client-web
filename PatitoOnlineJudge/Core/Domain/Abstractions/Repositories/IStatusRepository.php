<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IStatusRepository
{
    public function getStatusData($params, $limit);
}
