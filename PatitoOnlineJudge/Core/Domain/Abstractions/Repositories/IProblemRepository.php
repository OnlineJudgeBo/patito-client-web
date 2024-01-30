<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IProblemRepository {

    public function getProblemById($pid);

    public function getProblemByContestId($cid, $pid);

    public function getProblemsCount();

    public function getProblems($offset, $limit);
}
