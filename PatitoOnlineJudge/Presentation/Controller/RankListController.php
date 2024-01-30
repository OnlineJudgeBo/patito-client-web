<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IRankListService;

class RankListController
{
    private $rankListService;
    public $view_title;
    public $scope;
    public $rank;

    public function __construct(IRankListService $rankListService)
    {
        $this->view_title = "Ranking";
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
        $rankList = $this->rankListService->getRankListByDate($this->scope, $this->rank);
        require_once __DIR__ . "/../Views//ranklist.php";
    }
}
