<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;

class ProblemController
{
    private $problemService;
    public $title;
    private $cid;
    private $pid;

    public function __construct(IProblemService $problemService)
    {
        $this->title = "Problema";
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
        $title = $this->title;
        if (intval($this->pid) >= 0 && intval($this->cid) > 0) {
            $num = $this->pid;
            $cid = $this->cid;
            $problem = $this->problemService->getProblemByContestId($this->cid, $this->pid);
        } else {
            $problem = $this->problemService->getProblemById($this->pid);
        }
        require_once __DIR__ . "/../Views/problem.php";
    }
}
