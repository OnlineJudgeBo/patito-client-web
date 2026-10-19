<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRepository
{
    public function isContestActive($cid, $site_id);

    public function isContestAcceptingSubmissions($cid, $site_id);

    public function isVirtualContest($cid, $site_id);

    public function getContestById($cid);

    public function getAllContests($site_id, $year = null);

    public function getActiveContests($site_id, $limit);

    public function getClarifications($cid, $site_id, $user_id);

    public function getLastClarificationQuestionAt($cid, $site_id, $user_id);

    public function countPendingClarificationQuestions($cid, $site_id, $user_id);

    public function addClarificationQuestion($cid, $site_id, $user_id, $num, $question);

    public function getContestYears($site_id);

    public function getOfficialContests($site_id);

    public function getProblemsByContestId($cid, $site_id);

    public function isContestByIdPublic($cid);

    public function getAcProblemsByIdContest($cid, $site_id);

    public function getProblemTitleByNumber($cid, $num);

    public function getProblemIdByNum($cid, $num);

    public function getLanguagesAvailable($cid);

    public function getAllLanguages();
}
