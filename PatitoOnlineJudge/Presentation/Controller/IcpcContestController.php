<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Application\Services\ContestService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

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
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $contest_type = "official";
        $contest_list = $this->contestService->getAllContestDetails($contest_type);
        require_once $current_theme . "/icpcContest.php";
    }
}
