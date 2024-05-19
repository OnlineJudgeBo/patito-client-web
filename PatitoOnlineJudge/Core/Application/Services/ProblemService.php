<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use Exception;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Infraestructure\Database\Implementations\ProblemRepository;

class ProblemService implements IProblemService
{
    private $problemRepository;
    private $userId;

    public function __construct(IProblemRepository $problemRepository)
    {
        $this->problemRepository = $problemRepository;
    }

    public function addUserid($userId)
    {
        $this->userId = $userId;
    }

    public function getProblemById($pid)
    {
        //if ($this->isProblemInContest($pid)) {
        //    throw new Exception("Actualmente, el problema {$pid} no se puede ver porque está siendo utilizado en un contest.");
        //}
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
        if (empty($this->userId)) {
            return $this->problemRepository->getProblems($offset, $limit);
        }
        return $this->problemRepository->getProblemsByUser($offset, $limit, $this->userId);
    }

    public function isProblemInContest(int $pid): bool
    {
        return $this->problemRepository->isProblemInContest($pid);
    }
}
