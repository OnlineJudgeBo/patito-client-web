<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\ContestRepository;

class ContestService
{
    protected $contestRepository;

    public function __construct(ContestRepository $contestRepository)
    {
        $this->contestRepository = $contestRepository;
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

    public function isContestByIdPublic($cid) {
        return $this->contestRepository->isContestByIdPublic($cid);
    }
}
