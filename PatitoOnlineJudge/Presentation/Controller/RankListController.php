<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IRankListService;

class RankListController
{
    private $rankListService;
    public $title;
    public $scope;
    public $rank;

    public function __construct(IRankListService $rankListService)
    {
        $this->title = "Ranking";
        $this->rankListService = $rankListService;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function render()
    {
        $title = $this->title;
        $rankList = $this->rankListService->getRankListByDate($this->scope, $this->rank);
        require_once __DIR__ . "/../Views//ranklist.php";
    }
}
