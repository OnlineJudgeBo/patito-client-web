<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemStatusService;

class ProblemStatusController
{
    private $problemStatusService;
    private $problemService;
    public $view_title;
    public $problem_id;

    public function __construct(IProblemStatusService $problemStatusService, IProblemService $problemService)
    {
        $this->view_title = "Envios";
        $this->problemStatusService = $problemStatusService;
        $this->problemService = $problemService;

    }

    public function addProblemId($problem_id)
    {
        $this->problem_id = $problem_id;
    }

    public function render()
    {
        $userStatics = $this->problemStatusService->getUserStatics($this->problem_id);
        $topUsersByProblem = $this->problemStatusService->getTopUsersByProblem($this->problem_id);
        $problem = $this->problemService->getProblemById($this->problem_id);
        require_once __DIR__ . "/../Views/problemstatus.php";
    }
}
