<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\RankListRepository;

class RankListService
{
    private $rankListRepository;

    public function __construct(RankListRepository $rankListRepository)
    {
        $this->rankListRepository = $rankListRepository;
    }

    public function getRankListByDate($rank, $scope)
    {
        return $this->rankListRepository->getRankListByDate($rank, $scope);
    }
}
