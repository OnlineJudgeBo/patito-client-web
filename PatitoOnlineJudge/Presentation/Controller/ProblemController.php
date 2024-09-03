<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class ProblemController
{
    private IProblemService $problemService;
    private IContestService $contestService;
    public $title;
    private $cid;
    private $pid;
    private $cType;
    private $siteId;

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

    public function setCtype($cType)
    {
        $this->cType = $cType;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $isContestActive = true;
        if (intval($this->pid) >= 0 && intval($this->cid) > 0) {
            $num = $this->pid;
            $cid = $this->cid;
            $cType = $this->cType;
            $problem = $this->problemService->getProblemByContestId($this->cid, $this->pid, $this->cType);
            $isContestActive = $this->contestService->isContestActive($this->cid, $this->cType);
        } else {
            //try {
                $problem = $this->problemService->getProblemById($this->pid);
            //} catch (\Exception $e) {
                //$error = $e->getMessage();
                //require_once __DIR__."/../Views/genericError.php";
                //die();
            //}
        }
        require_once $current_theme . "/problem.php";
    }
}
