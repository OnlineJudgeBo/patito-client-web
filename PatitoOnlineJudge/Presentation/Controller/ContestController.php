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
        $availableYears = $this->contestService->getContestYears();
        $selectedYear = isset($_GET['year']) ? intval($_GET['year']) : 0;
        if (!in_array($selectedYear, $availableYears, true)) {
            $selectedYear = 0;
        }
        $contest_list = $this->contestService->getAllContestDetails(
            $contest_type,
            $selectedYear > 0 ? $selectedYear : null
        );
        require_once "$current_theme/contest.php";
    }
}
