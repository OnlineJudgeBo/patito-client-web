<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use Exception;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemService;
use PatitoOnlineJudge\Infrastructure\Database\Implementations\ProblemRepository;

class ProblemService implements IProblemService
{
    private $problemRepository;
    private $userId;
    private $site_id;

    public function __construct(IProblemRepository $problemRepository)
    {
        $this->problemRepository = $problemRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function addUserid($userId)
    {
        $this->userId = $userId;
    }

    public function getProblemById($pid)
    {
        return $this->problemRepository->getProblemById($pid, $this->site_id);
    }

    public function getProblemByAcademicAssignment($pid, $courseId, $assignmentId, $userId)
    {
        return $this->problemRepository->getProblemByAcademicAssignment(
            $pid,
            $courseId,
            $assignmentId,
            $userId,
            $this->site_id
        );
    }

    public function getProblemByContestId($cid, $pid, $cType)
    {
        if ($cType == "official_contest") {
            return $this->problemRepository->getProblemByOfficialContestId($cid, $pid, $this->site_id);
        } else {
            return $this->problemRepository->getProblemByContestId($cid, $pid, $this->site_id);
        }
    }

    public function getProblemsCount()
    {
        return $this->problemRepository->getProblemsCount($this->site_id);
    }

    public function getProblems($offset, $limit)
    {
        if (empty($this->userId)) {
            return $this->problemRepository->getProblems($offset, $limit, $this->site_id);
        }
        return $this->problemRepository->getProblemsByUser($offset, $limit, $this->userId, $this->site_id);
    }

    public function isProblemInContest(int $pid): bool
    {
        return $this->problemRepository->isProblemInContest($pid, $this->site_id);
    }
}
