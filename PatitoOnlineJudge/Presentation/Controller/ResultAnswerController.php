<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class ResultAnswerController
{
    private $solutionService;
    public $view_title;
    private $solution_id;

    public function __construct(ISolutionService $solutionService)
    {
        $this->view_title = "Contests";
        $this->solutionService = $solutionService;
    }

    public function addSolutionId($solution_id)
    {
        $this->solution_id = $solution_id;
    }

    public function render()
    {

        $solution_id = $this->solution_id;
        $result = $this->solutionService->getErrorResult($solution_id);
        if (empty($result["error"])) {
            $result["error"] = "";
        }
        require_once __DIR__ . "/../Views/resultAnswer.php";
    }
}
