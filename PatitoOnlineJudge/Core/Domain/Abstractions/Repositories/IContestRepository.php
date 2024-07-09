<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRepository
{
    public function isContestActive($cid);

    public function getContestById($cid);

    public function getAllContests();

    public function getOfficialContests();

    public function getProblemsByContestId($cid);

    public function isContestByIdPublic($cid);

    public function getAcProblemsByIdContest($cid);

    public function getProblemTitleByNumber($cid, $num);

    public function getProblemIdByNum($cid, $num);

    public function getLanguagesAvailable($cid);

    public function getAllLanguages();
}
