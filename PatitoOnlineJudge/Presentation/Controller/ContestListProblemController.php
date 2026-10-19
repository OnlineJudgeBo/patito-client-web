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
        if (!$contestDetail) {
            $contestProblemList = array();
            $error = "Concurso no encontrado.";
            require_once $current_theme . "/error.php";
            return;
        }

        // Before the start the page still opens, without problems, so participants can ask questions.
        $error = null;
        if (!$this->isAdministrator()) {
            if ($this->contestHasNotStarted($contestDetail)) {
                $error = "Este concurso aún no inició.";
            } elseif (!$this->contestService->isContestActive($this->cid)) {
                $contestProblemList = array();
                $error = "Este concurso no está activo.";
                require_once $current_theme . "/error.php";
                return;
            }
        }

        if ($this->userHasAccess()) {
            $user_id = $_SESSION["user_id"] ?? null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["clarification"])) {
                $this->askClarification($user_id);
                return;
            }
            if (isset($_GET["clarifications"])) {
                $this->renderClarificationsVersion($user_id);
                return;
            }
            $clarifications = $this->contestService->getClarifications($this->cid, $user_id);
            $clarificationsVersion = $this->clarificationsVersion($clarifications);
            $clarificationError = $_SESSION["clarification_error"] ?? null;
            unset($_SESSION["clarification_error"]);
            if (empty($_SESSION["clarification_token"])) {
                $_SESSION["clarification_token"] = bin2hex(random_bytes(16));
            }
            $clarificationToken = $_SESSION["clarification_token"];
            $problems = $this->contestService->getContestProblems($this->cid);
            $clarificationNums = array_map('intval', array_column($problems, "pnum"));
            $contestProblemList = $error ? array() : $problems;
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

    // Post/redirect/get back to the clarifications section, with the error (if any) in the session.
    private function askClarification($user_id)
    {
        $error = null;
        if (empty($user_id)) {
            $error = "Inicia sesión para preguntar.";
        } elseif (!hash_equals($_SESSION["clarification_token"] ?? "", (string) ($_POST["token"] ?? ""))) {
            $error = "La sesión cambió; vuelve a enviar la pregunta.";
        } else {
            $num = ($_POST["problem_num"] ?? "") === "" ? null : intval($_POST["problem_num"]);
            $error = $this->contestService->askClarification(intval($this->cid), $user_id, $num, $_POST["question"] ?? "");
        }
        $_SESSION["clarification_error"] = $error;
        $type = $this->cType ? "&type=" . urlencode($this->cType) : "";
        header("Location: contest.php?cid=" . intval($this->cid) . $type . "#aclaraciones");
    }

    // The page polls this to warn about new announcements or answers without reloading.
    private function renderClarificationsVersion($user_id)
    {
        header("Content-Type: application/json");
        echo json_encode(["version" => $this->clarificationsVersion($this->contestService->getClarifications($this->cid, $user_id))]);
    }

    private function clarificationsVersion($clarifications)
    {
        $latest = "";
        foreach ($clarifications as $item) {
            $latest = max($latest, $item["answered_at"] ?? "", $item["created_at"]);
        }
        return count($clarifications) . "|" . $latest;
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
