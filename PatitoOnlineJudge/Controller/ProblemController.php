<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\ProblemService;

class ProblemController
{
    private $problemService;
    public $view_title;
    private $cid;
    private $pid;

    public function __construct(ProblemService $problemService)
    {
        $this->view_title = "Problema";
        $this->problemService = $problemService;
    }

    public function setProblemId($pid)
    {
        $this->pid = $pid;
    }

    public function setContestId($cid)
    {
        $this->cid = $cid;
    }

    public function render()
    {
        if (intval($this->pid) >= 0 && intval($this->cid) > 0) {
            $problem = $this->problemService->getProblemByContestId($this->cid, $this->pid);
        } else {
            $problem = $this->problemService->getProblemById($this->pid);
        }
        require_once __DIR__ . "/../Presentation//problem.php";
    }
}
