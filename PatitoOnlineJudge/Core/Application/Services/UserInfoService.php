<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IUserInfoService;

class UserInfoService implements IUserInfoService
{
    private $solutionRepository;
    private $loginRepository;
    private $site_id;

    public function __construct(
        ISolutionRepository $solutionRepository,
        ILoginRepository $loginRepository
        )
    {
        $this->solutionRepository = $solutionRepository;
        $this->loginRepository = $loginRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function getSummarySolutions($userId, $type) {
        if ($type == "ac") {
            $solutions = $this->solutionRepository->getSummarySolutions($userId, $this->site_id);
            $solutions = $this->summarizeAcceptedSolutions($solutions);
        } else if ($type == "error") {
            $solutions = $this->solutionRepository->getSummaryErrorSolutions($userId, $this->site_id);
        }
        return $solutions;
    }

    private function summarizeAcceptedSolutions(array $solutions): array
    {
        $summary = [];

        foreach ($solutions as $solution) {
            $problemId = $solution['problem_id'];

            if (!isset($summary[$problemId])) {
                $solution['first_solved_date'] = $solution['in_date'] ?? '';
                $solution['submission_count'] = 0;
                $summary[$problemId] = $solution;
            }

            $summary[$problemId]['submission_count']++;
        }

        return array_values($summary);
    }
}
