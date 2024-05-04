<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISolutionRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IStatusRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IStatusService;

class StatusService implements IStatusService
{
    private $solutionRepository;

    public function __construct(ISolutionRepository $solutionRepository)
    {
        $this->solutionRepository = $solutionRepository;
    }

    public function getStatusData($params)
    {
        if (isset($params["limit"])) {
            $limit = $params["limit"];
        } else {
            $limit = 500;
        }

        if (isset($params["user_id"])) {
            $limit = 100000;
        }
        return $this->solutionRepository->getStatusData($params, $limit);
    }
}
