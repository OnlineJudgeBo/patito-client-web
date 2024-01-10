<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\ContestService;
use PatitoOnlineJudge\Service\RankListService;

class RankListController
{
    private $rankListService;
    public $view_title;
    public $scope;
    public $rank;

    public function __construct(RankListService $rankListService)
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
        require_once __DIR__ . "/../../resources/View/ranklist.php";
    }
}
