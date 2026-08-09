<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IProblemRepository {

    public function getProblemById($pid, $site_id);

    public function getProblemByAcademicAssignment($pid, $courseId, $assignmentId, $userId, $siteId);

    public function getProblemByContestId($cid, $pid, $site_id);

    public function getProblemByOfficialContestId($cid, $pid, $site_id);

    public function getProblemsCount($site_id);

    public function getProblems($offset, $limit, $site_id);

    public function getProblemsByUser($offset, $limit, $userId, $site_id);

    public function isProblemInContest($problem_id, $site_id);
}
