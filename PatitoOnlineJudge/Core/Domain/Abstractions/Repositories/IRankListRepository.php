<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IRankListRepository
{
    public function getRankListByDate($scope, $rank);
}
