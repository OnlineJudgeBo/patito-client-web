<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IIcpcContestService;

class IcpcContestController
{
    private $icpcContestService;
    public $title;

    public function __construct(IIcpcContestService $icpcContestService)
    {
        $this->icpcContestService = $icpcContestService;
        $this->title = "Concursos Oficiales";
    }

    public function render()
    {
        $title = $this->title;
        $contest_list = $this->icpcContestService->getAllContestDetails();
        require_once __DIR__ . "/../Views/icpcContest.php";
    }
}
