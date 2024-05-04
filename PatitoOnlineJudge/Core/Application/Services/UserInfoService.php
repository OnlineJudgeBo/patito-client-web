<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ILoginRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IUserInfoService;

class UserInfoService implements IUserInfoService
{
    private $solutionRepository;
    private $loginRepository;

    public function __construct(
        ISolutionRepository $solutionRepository,
        ILoginRepository $loginRepository
        )
    {
        $this->solutionRepository = $solutionRepository;
        $this->loginRepository = $loginRepository;
    }

    public function getSummarySolutions($userId) {
        $solutions = $this->solutionRepository->getSummarySolutions($userId);
        return $solutions;
    }
}
