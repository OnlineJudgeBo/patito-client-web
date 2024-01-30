<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IRankListService
{

    public function getRankListByDate($rank, $scope);
}
