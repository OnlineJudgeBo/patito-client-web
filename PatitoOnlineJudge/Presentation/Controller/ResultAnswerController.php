<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class ResultAnswerController
{
    private $solutionService;
    public $title;
    private $solution_id;

    public function __construct(ISolutionService $solutionService)
    {
        $this->title = "Contests";
        $this->solutionService = $solutionService;
    }

    public function addSolutionId($solution_id)
    {
        $this->solution_id = $solution_id;
    }

    public function render()
    {
        $title = $this->title;
        $solution_id = $this->solution_id;
        $result = $this->solutionService->getErrorResult($solution_id);
        if (empty($result["error"])) {
            $result["error"] = "";
        }
        require_once __DIR__ . "/../Views/resultAnswer.php";
    }
}
