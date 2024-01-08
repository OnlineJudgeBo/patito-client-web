<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\ContestService;

class ContestController
{
    private $contestService;
    public $view_title;

    public function __construct(ContestService $contestService)
    {
        $this->view_title = "Contests";
        $this->contestService = $contestService;
    }

    public function render()
    {
        $contest_list = $this->contestService->getAllContestDetails();

        require_once __DIR__ . "/../../resources/View/contest.php";
    }
}
