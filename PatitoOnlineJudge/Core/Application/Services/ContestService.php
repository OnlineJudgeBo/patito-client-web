<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;

class ContestService implements IContestService
{
    protected $contestRepository;

    public function __construct(IContestRepository $contestRepository)
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
