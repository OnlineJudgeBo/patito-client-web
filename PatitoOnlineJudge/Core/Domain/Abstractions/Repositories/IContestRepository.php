<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRepository
{
    public function getContestById($cid);

    public function getAllContests();

    public function getProblemsByContestId($cid);

    public function isContestByIdPublic($cid);
}
