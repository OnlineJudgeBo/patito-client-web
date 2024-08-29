<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IRankListService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

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
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $realm = $_SERVER["REALM"];
        $rankList = $this->rankListService->getRankListByDate($this->scope, $this->rank, $realm);
        require_once $current_theme . "/ranklist.php";
    }
}
