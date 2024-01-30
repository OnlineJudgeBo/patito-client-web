<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Service\ContestService;

class ContestController
{
    private $contestService;
    public $view_title;

    public function __construct(IContestService $contestService)
    {
        $this->view_title = "Contests";
        $this->contestService = $contestService;
    }

    public function render()
    {
        $contest_list = $this->contestService->getAllContestDetails();

        require_once __DIR__ . "/../Views//contest.php";
    }
}
