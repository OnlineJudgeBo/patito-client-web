<?php
namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class SolutionService implements ISolutionService
{
    private $solutionRepository;

    public function __construct(ISolutionRepository $solutionRepository)
    {
        $this->solutionRepository = $solutionRepository;
    }

    public function getLastRuns()
    {
        return $this->solutionRepository->getLastRuns();
    }

    public function getErrorResult($solutionId)
    {
        return $this->solutionRepository->getErrorResult($solutionId);
    }
}
