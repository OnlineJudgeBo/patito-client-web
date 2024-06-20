<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IIcpcContestRepository
{
    public function isContestActive($cid);

    public function getContestById($cid);

    public function getAllContests() : array;

    public function getAllContestsForAdmin() : array;

    public function getProblemsByContestId($cid);

    public function isContestByIdPublic($cid);

    public function getAcProblemsByIdContest($cid);

    public function getProblemTitleByNumber($cid, $num);

    public function getProblemIdByNum($cid, $num);

    public function getLanguagesAvailable($cid);

    public function getAllLanguages();
}
