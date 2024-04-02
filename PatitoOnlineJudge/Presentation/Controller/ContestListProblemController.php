<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Service\ContestService;

class ContestListProblemController
{
    private $contestService;
    public $title;
    public $cid;

    public function __construct(IContestService $contestService)
    {
        $this->title = "Lista de problemas";
        $this->contestService = $contestService;
    }
    
    public function addCid($cid) {
        $this->cid = $cid;
    }

    private function userHasAccess()
    {
        if (
            isset($_SESSION['administrator']) || isset($_SESSION["m$this->cid"])
            || isset($_SESSION["contest_creator"])
            || isset($_SESSION["c$this->cid"])
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
        $title = $this->title;
        if ($this->userHasAccess()) {
                $contestProblemList = $this->contestService->getContestProblems($this->cid);
                $contestDetail = $this->contestService->getContestById($this->cid);
                $resolveBy = $this->contestService->getAcProblemsByIdContest($this->cid);
                if (isset($_SESSION["user_id"])) {
                    $user_id = $_SESSION["user_id"];
                }
    
                $cid = $this->cid;
                require_once __DIR__ . "/../Views/contestProblemList.php";
        } else {
            $contestDetail = $this->contestService->getContestById($this->cid);
            $contestProblemList = array();
            $error = "Este contest es privado";
            require_once __DIR__ . "/../Views/error.php";
        }
    }
}
