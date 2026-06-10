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

    public function isVirtualContest($cid)
    {
        return $this->contestRepository->isVirtualContest($cid, $this->site_id);
    }

    public function getContestProblems($cid)
    {
        return $this->contestRepository->getProblemsByContestId($cid, $this->site_id);
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
