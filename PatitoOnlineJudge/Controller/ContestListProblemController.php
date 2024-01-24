<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\ContestService;

class ContestListProblemController
{
    private $contestService;
    public $viewTitle;
    public $cid;

    public function __construct(ContestService $contestService, $cid)
    {
        $this->viewTitle = "Lista de problemas";
        $this->contestService = $contestService;
        $this->cid = $cid;
    }

    private function userHasAccess()
    {
        if (
            isset($_SESSION['administrator']) || isset($_SESSION["m$this->cid"])
            || isset($_SESSION["contest_creator"])
        ) {
            return true;
        }

        if ($this->contestService->isContestByIdPublic($this->cid)) {
            return true;
        }
        return false;
    }

    public function render()
    {
        if ($this->userHasAccess()) {
            $contestProblemList = $this->contestService->getContestProblems($this->cid);
            $contestDetail = $this->contestService->getContestById($this->cid);

            $cid = $this->cid;
            require_once __DIR__ . "/../../resources/View/contestProblemList.php";
        } else {
            $contestDetail = $this->contestService->getContestById($this->cid);
            $contestProblemList = array();
            $error = "Este contest es privado";
            require_once __DIR__ . "/../../resources/View/contestProblemList.php";
        }
    }
}
