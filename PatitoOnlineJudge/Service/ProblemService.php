<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\ProblemRepository;

class ProblemService
{
    private $problemRepository;

    public function __construct(ProblemRepository $problemRepository)
    {
        $this->problemRepository = $problemRepository;
    }

    public function getProblemById($pid)
    {
        return $this->problemRepository->getProblemById($pid);
    }

    public function getProblemByContestId($cid, $pid)
    {
        return $this->problemRepository->getProblemByContestId($cid, $pid);
    }

    public function getProblemsCount()
    {
        return $this->problemRepository->getProblemsCount();
    }

    public function getProblems($offset, $limit)
    {
        return $this->problemRepository->getProblems($offset, $limit);
    }
}
