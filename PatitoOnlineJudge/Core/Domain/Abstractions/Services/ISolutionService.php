<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ISolutionService
{
    public function getLastRuns();
    public function getErrorResult($solutionId, $includeTests = true);
    public function getStatusData($params, $limit);
}
