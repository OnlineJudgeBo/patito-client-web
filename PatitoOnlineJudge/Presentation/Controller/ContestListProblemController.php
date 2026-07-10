<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class ContestListProblemController
{
    private $contestService;
    public $title;
    public $cid;
    public $cType;

    public function __construct(IContestService $contestService)
    {
        $this->title = "Lista de problemas";
        $this->contestService = $contestService;
    }

    public function addCid($cid)
    {
        $this->cid = $cid;
    }

    public function addCtype($cType)
    {
        $this->cType = $cType;
    }

    private function isAdministrator()
    {
        return isset($_SESSION["Administrador"]) && $_SESSION["Administrador"] == "Administrador";
    }

    private function userHasAccess()
    {
        if (

            $this->isAdministrator() ||
            (isset($_SESSION["Docente"])       && $_SESSION["Docente"]       == "Docente")       ||
            (isset($_SESSION["Auxiliar"])      && $_SESSION["Auxiliar"]      == "Auxiliar")      ||
            isset($_SESSION["c$this->cid"])
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
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $contestDetail = $this->contestService->getContestById($this->cid);

        if (!$this->isAdministrator()) {
            if ($this->contestHasNotStarted($contestDetail)) {
                $contestProblemList = array();
                $error = "Este concurso aún no inició.";
                require_once $current_theme . "/error.php";
                return;
            }

            if (!$this->contestService->isContestActive($this->cid)) {
                $contestProblemList = array();
                $error = "Este concurso no está activo.";
                require_once $current_theme . "/error.php";
                return;
            }
        }

        if ($this->userHasAccess()) {
            $contestProblemList = $this->contestService->getContestProblems($this->cid);
            $resolveBy = $this->contestService->getAcProblemsByIdContest($this->cid);
            if (isset($_SESSION["user_id"])) {
                $user_id = $_SESSION["user_id"];
            }

            $cid = $this->cid;
            $cType = $this->cType;
            require_once $current_theme . "/contestProblemList.php";
        } else {
            $contestProblemList = array();
            $error = "Este concurso es privado. Por favor, contacta con el creador del concurso para más información.";
            require_once $current_theme . "/error.php";
        }
    }

    private function contestHasNotStarted($contestDetail): bool
    {
        if (empty($contestDetail["start_time"])) {
            return false;
        }

        $startTime = strtotime(str_replace("-", "/", $contestDetail["start_time"]));
        return $startTime !== false && time() < $startTime;
    }
}
