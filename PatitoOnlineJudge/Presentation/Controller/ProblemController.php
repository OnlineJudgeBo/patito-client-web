<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;

class ProblemController
{
    private IProblemService $problemService;
    private IContestService $contestService;
    public $title;
    private $cid;
    private $pid;

    public function __construct(IProblemService $problemService, IContestService $contestService)
    {
        $this->title = "Problema";
        $this->problemService = $problemService;
        $this->contestService = $contestService;
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
        $isContestActive = true;
        if (intval($this->pid) >= 0 && intval($this->cid) > 0) {
            $num = $this->pid;
            $cid = $this->cid;
            $problem = $this->problemService->getProblemByContestId($this->cid, $this->pid);
            $isContestActive = $this->contestService->isContestActive($this->cid);
        } else {
            try {
                $problem = $this->problemService->getProblemById($this->pid);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__."/../Views/genericError.php";
                die();
            }
        }
        require_once __DIR__ . "/../Views/problem.php";
    }
}
