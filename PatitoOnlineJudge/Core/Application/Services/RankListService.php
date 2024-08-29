<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IRankListRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IRankListService;

class RankListService implements IRankListService
{
    private $rankListRepository;

    public function __construct(IRankListRepository $rankListRepository)
    {
        $this->rankListRepository = $rankListRepository;
    }

    public function getRankListByDate($rank, $scope)
    {
        return $this->rankListRepository->getRankListByDate($rank, $scope);
    }
}
