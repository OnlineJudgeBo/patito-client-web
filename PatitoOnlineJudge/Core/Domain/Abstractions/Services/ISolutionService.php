<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ISolutionService
{
    public function getLastRuns();
    public function getErrorResult($solutionId);
    public function getStatusData($params, $limit);
}
