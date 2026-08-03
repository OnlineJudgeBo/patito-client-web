<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IContestService
{
    public function isContestActive($cid);

    public function isContestAcceptingSubmissions($cid);

    public function isVirtualContest($cid);

    public function getContestProblems($cid);

    public function getContestById($cid);

    public function getAllContestDetails($contest_type, $year = null);

    public function getContestYears();

    public function isContestByIdPublic($cid);

    public function getAcProblemsByIdContest($cid);

    public function getProblemTitleByNumber($cid, $num);

    public function getProblemIdByNum($cid, $pid);

    public function languagesAvailable($cid);
}
