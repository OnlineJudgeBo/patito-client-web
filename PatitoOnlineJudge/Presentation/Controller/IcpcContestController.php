<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Application\Services\ContestService;

class IcpcContestController
{
    private $contestService;
    public $title;

    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
        $this->title = "Concursos Oficiales";
    }

    public function render()
    {
        $title = $this->title;
        $contest_type = "official";
        $contest_list = $this->contestService->getAllContestDetails($contest_type);
        require_once __DIR__ . "/../Views/icpcContest.php";
    }
}
