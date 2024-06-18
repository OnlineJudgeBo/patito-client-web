<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IIcpcContestService;

class IcpcContestController
{
    private $contestService;
    public $title;

    public function __construct(IIcpcContestService $contestService)
    {
        $this->title = "Concursos Oficiales";
        $this->contestService = $contestService;
    }

    public function render()
    {
        $title = $this->title;
        $contest_list = $this->contestService->getAllContestDetails();
        require_once __DIR__ . "/../Views/icpcContest.php";
    }
}
