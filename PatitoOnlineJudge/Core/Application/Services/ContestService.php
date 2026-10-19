<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;

class ContestService implements IContestService
{
    protected $contestRepository;
    private $site_id;

    public function __construct(IContestRepository $contestRepository)
    {
        $this->contestRepository = $contestRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function isContestActive($cid)
    {
        return $this->contestRepository->isContestActive($cid, $this->site_id);
    }

    public function isContestAcceptingSubmissions($cid)
    {
        return $this->contestRepository->isContestAcceptingSubmissions($cid, $this->site_id);
    }

    public function isVirtualContest($cid)
    {
        return $this->contestRepository->isVirtualContest($cid, $this->site_id);
    }

    public function getContestProblems($cid)
    {
        return $this->contestRepository->getProblemsByContestId($cid, $this->site_id);
    }

    public function getActiveContests($limit = 5)
    {
        return $this->contestRepository->getActiveContests($this->site_id, $limit);
    }

    public function getClarifications($cid, $user_id)
    {
        return $this->contestRepository->getClarifications($cid, $this->site_id, $user_id);
    }

    // Same rules as the admin API: before or during the contest, never after; one question every 30 seconds,
    // at most 5 unanswered per participant.
    // Returns the error to show, or null when the question was saved.
    public function askClarification($cid, $user_id, $num, $question)
    {
        $contest = $this->contestRepository->getContestById($cid);
        if (!$contest) {
            return "Concurso no encontrado.";
        }
        if (strtotime($contest["end_time"]) < time()) {
            return "El concurso ya terminó; no se reciben más preguntas.";
        }
        $question = trim((string) $question);
        if ($question === "" || mb_strlen($question) > 2000) {
            return "La pregunta debe tener entre 1 y 2000 caracteres.";
        }
        if ($num !== null) {
            $nums = array_map('intval', array_column($this->contestRepository->getProblemsByContestId($cid, $this->site_id), "pnum"));
            if (!in_array($num, $nums, true)) {
                return "El problema no pertenece al concurso.";
            }
        }
        $last = $this->contestRepository->getLastClarificationQuestionAt($cid, $this->site_id, $user_id);
        if ($last && time() - strtotime($last) < 30) {
            return "Espera unos segundos antes de enviar otra pregunta.";
        }
        if ($this->contestRepository->countPendingClarificationQuestions($cid, $this->site_id, $user_id) >= 5) {
            return "Tienes 5 preguntas sin responder; espera a que el juez las responda.";
        }
        $this->contestRepository->addClarificationQuestion($cid, $this->site_id, $user_id, $num, $question);
        return null;
    }

    public function getContestById($cid)
    {
        return $this->contestRepository->getContestById($cid);
    }

    public function getAllContestDetails($contest_type, $year = null)
    {

        if ($contest_type == "official") {
            return $this->contestRepository->getOfficialContests($this->site_id);
        } else {
            return $this->contestRepository->getAllContests($this->site_id, $year);
        }
    }

    public function getContestYears()
    {
        return $this->contestRepository->getContestYears($this->site_id);
    }

    public function isContestByIdPublic($cid)
    {
        return $this->contestRepository->isContestByIdPublic($cid);
    }

    public function getAcProblemsByIdContest($cid)
    {
        $data = $this->contestRepository->getAcProblemsByIdContest($cid, $this->site_id);
        $result = array();
        foreach ($data as $value) {
            $result[$value["user_id"]][] = $value["num"];
        }
        return $result;
    }

    public function getProblemTitleByNumber($cid, $num)
    {
        return $this->contestRepository->getProblemTitleByNumber($cid, $num);
    }

    public function getProblemIdByNum($cid, $pid)
    {
        return $this->contestRepository->getProblemIdByNum($cid, $pid);
    }

    public function languagesAvailable($cid)
    {
        if ($cid > 0) {
            return $this->contestRepository->getLanguagesAvailable($cid);
        } else {
            return $this->contestRepository->getAllLanguages();
        }
    }
}
