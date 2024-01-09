<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\ProblemService;

class ProblemSetController
{
    private $problemService;
    public $view_title;
    private $page;

    public function __construct(ProblemService $problemService)
    {
        $this->view_title = "Problema";
        $this->problemService = $problemService;
    }

    public function setProblemSetPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        if (intval($this->page) == 0) {
            $this->page = 0;
        }
        $totalProblems = $this->problemService->getProblemsCount();
        $limit = 100;
        $totalPages = ceil($totalProblems / $limit);
        $offset = ( intval($this->page)) * $limit;
        $problems = $this->problemService->getProblems($offset, $limit);
        require_once __DIR__ . "/../../resources/View/problemset.php";
    }
}
