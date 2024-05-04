<?php
namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface ISolutionRepository
{
    public function getLastRuns();

    public function getErrorResult($solution_id);

    public function getStatusData($params, $limit);

    public function getSummarySolutions($user_id);
}
