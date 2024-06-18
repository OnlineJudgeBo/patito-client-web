<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IIcpcContestRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IIcpcContestService;

class IcpcContestService implements IIcpcContestService
{
    protected $contestRepository;

    public function __construct(IIcpcContestRepository $contestRepository)
    {
        $this->contestRepository = $contestRepository;
    }

    public function isContestActive($cid)
    {
        return $this->contestRepository->isContestActive($cid);
    }

    public function getContestProblems($cid)
    {
        return $this->contestRepository->getProblemsByContestId($cid);
    }

    public function getContestById($cid)
    {
        return $this->contestRepository->getContestById($cid);
    }

    public function getAllContestDetails()
    {
        return $this->contestRepository->getAllContests();
    }

    public function isContestByIdPublic($cid)
    {
        return $this->contestRepository->isContestByIdPublic($cid);
    }

    public function getAcProblemsByIdContest($cid)
    {
        $data = $this->contestRepository->getAcProblemsByIdContest($cid);
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
