<?php
namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class SolutionService implements ISolutionService
{
    private $solutionRepository;
    private $site_id;

    public function __construct(ISolutionRepository $solutionRepository)
    {
        $this->solutionRepository = $solutionRepository;
        $this->site_id = $_SERVER["SITE_ID"];

    }

    public function getStatusData($params, $limit) {
        return $this->solutionRepository->getStatusData($params, $limit, $this->site_id);
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
