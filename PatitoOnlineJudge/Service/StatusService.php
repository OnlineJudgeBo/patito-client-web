<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\StatusRepository;

class StatusService
{
    private $statusRepository;

    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function getStatusData($params)
    {
        return $this->statusRepository->getStatusData($params);
    }
}
