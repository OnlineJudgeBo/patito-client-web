<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;


interface IProblemService
{

    public function addUserid($userId);

    public function getProblemById($pid);

    public function getProblemByContestId($cid, $pid);

    public function getProblemsCount();

    public function getProblems($offset, $limit);

    public function isProblemInContest(int $pid): bool;
}
