<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;

class ProblemSetController
{
    private $problemService;
    public $title;
    private $page;

    public function __construct(IProblemService $problemService)
    {
        $this->title = "Problema";
        $this->problemService = $problemService;
    }

    public function setProblemSetPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        $title = $this->title;
        if (intval($this->page) == 0) {
            $this->page = 0;
        }
        $totalProblems = $this->problemService->getProblemsCount();
        $limit = 100;
        $totalPages = ceil($totalProblems / $limit);
        $offset = ( intval($this->page)) * $limit;
        $problems = $this->problemService->getProblems($offset, $limit);
        
        $user_id = "";
        if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] > 0) {
            $user_id = $_SESSION["user_id"];
        }

        require_once __DIR__ . "/../Views/problemset.php";
    }
}
