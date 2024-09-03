<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IRankListRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IRankListService;

class RankListService implements IRankListService
{
    private $rankListRepository;
    private $site_id;

    public function __construct(IRankListRepository $rankListRepository)
    {
        $this->rankListRepository = $rankListRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function getRankListByDate($rank, $scope)
    {
        return $this->rankListRepository->getRankListByDate($rank, $scope, $this->site_id);
    }
}
