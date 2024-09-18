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
        } else if ($type == "error") {
            $solutions = $this->solutionRepository->getSummaryErrorSolutions($userId, $this->site_id);
        }
        return $solutions;
    }
}
