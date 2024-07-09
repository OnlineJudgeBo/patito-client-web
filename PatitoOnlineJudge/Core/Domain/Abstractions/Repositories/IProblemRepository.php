<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IProblemRepository {

    public function getProblemById($pid);

    public function getProblemByContestId($cid, $pid);

    public function getProblemByOfficialContestId($cid, $pid);

    public function getProblemsCount();

    public function getProblems($offset, $limit);

    public function getProblemsByUser($offset, $limit, $userId);

    public function isProblemInContest($problem_id);
}
