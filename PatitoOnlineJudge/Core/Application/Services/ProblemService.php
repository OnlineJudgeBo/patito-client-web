<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemRepository;

class ProblemService implements IProblemService
{
    private $problemRepository;

    public function __construct(IProblemRepository $problemRepository)
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
