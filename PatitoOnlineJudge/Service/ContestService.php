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

    public function getContestDetails($cid)
    {
    }

    public function getAllContestDetails()
    {
        return $this->contestRepository->getAllContests();
    }
}
