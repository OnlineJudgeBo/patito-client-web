<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;

class ContestController
{
    private $contestService;
    public $title;

    public function __construct(IContestService $contestService)
    {
        $this->title = "Contests";
        $this->contestService = $contestService;
    }

    public function render()
    {
        $title = $this->title;
        $contest_type = "no_official";
        $contest_list = $this->contestService->getAllContestDetails($contest_type);
        require_once __DIR__ . "/../Views/contest.php";
    }
}
