<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

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
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $solution_id = $this->solution_id;
        $isStaff = isset($_SESSION["Administrador"]) || isset($_SESSION["Docente"]) || isset($_SESSION["Auxiliar"]);
        $result = $this->solutionService->getErrorResult($solution_id, $isStaff);
        if (empty($result["error"])) {
            $result["error"] = "";
        }
        require_once $current_theme . "/resultAnswer.php";
    }
}
