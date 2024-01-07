<?php
namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\SolutionRepository;

class SolutionService
{
    private $solutionRepository;

    public function __construct(SolutionRepository $solutionRepository)
    {
        $this->solutionRepository = $solutionRepository;
    }

    public function getLastRuns()
    {
        return $this->solutionRepository->getLastRuns();
    }
}
