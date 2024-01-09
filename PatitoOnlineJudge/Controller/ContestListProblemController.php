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

    public function render()
    {
        $contestProblemList = $this->contestService->getContestProblems($this->cid);
        $contestDetail = $this->contestService->getContestById($this->cid);

        $cid = $this->cid;
        require_once __DIR__ . "/../../resources/View/contestProblemList.php";
    }
}
