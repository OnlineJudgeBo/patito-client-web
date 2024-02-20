<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestRankService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;

class ContestRankController
{
    private $contestRankService;
    private $contestService;
    public $title;
    private $cid;

    public function __construct(IContestRankService $contestRankService, IContestService $contestService)
    {
        $this->title = "Contests";
        $this->contestRankService = $contestRankService;
        $this->contestService = $contestService;
    }

    public function addCid($cid)
    {
        $this->cid = $cid;
    }

    public function render()
    {
        $title = $this->title;
        $contest = $this->contestService->getContestById($this->cid);
        $problems = $this->contestService->getContestProblems($this->cid);
        require __DIR__ . "/../../../Legacy/Include/const.inc.php";

        $start_time = strtotime($contest["start_time"]);
        $end_time = strtotime($contest["end_time"]);
        $obi = 0;
        $contestRank = $this->contestRankService->getContestRankListById($this->cid, $start_time, $end_time);
        $first_blood = $this->contestRankService->getFirstBlood($this->cid);
        $sec2str = function ($sec) {
            return sprintf("%02d:%02d:%02d", $sec / 3600, $sec % 3600 / 60, $sec % 60);
        };

        if (isset($this->cid)) {
            $cid = $this->cid;
        }

        require_once __DIR__ . "/../Views/contestRank.php";
    }
}
