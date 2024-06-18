<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IIcpcContestService
{
    public function isContestActive($cid);

    public function getContestProblems($cid);

    public function getContestById($cid);

    public function getAllContestDetails();

    public function isContestByIdPublic($cid);

    public function getAcProblemsByIdContest($cid);

    public function getProblemTitleByNumber($cid, $num);

    public function getProblemIdByNum($cid, $pid);

    public function languagesAvailable($cid);
}
