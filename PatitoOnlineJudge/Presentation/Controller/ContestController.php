<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

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
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $contest_type = "no_official";
        $contest_list = $this->contestService->getAllContestDetails($contest_type);
        require_once "$current_theme/contest.php";
    }
}
