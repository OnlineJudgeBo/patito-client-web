<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemStatusService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class ProblemStatusController
{
    private $problemStatusService;
    private $problemService;
    public $title;
    public $problem_id;

    public function __construct(IProblemStatusService $problemStatusService, IProblemService $problemService)
    {
        $this->title = "Envios";
        $this->problemStatusService = $problemStatusService;
        $this->problemService = $problemService;

    }

    public function addProblemId($problem_id)
    {
        $this->problem_id = $problem_id;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $userStatics = $this->problemStatusService->getUserStatics($this->problem_id);
        $topUsersByProblem = $this->problemStatusService->getTopUsersByProblem($this->problem_id);
        $problem = $this->problemService->getProblemById($this->problem_id);
        require_once $current_theme . "/problemstatus.php";
    }
}
