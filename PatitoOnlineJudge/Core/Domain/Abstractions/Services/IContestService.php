<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IContestService
{
    public function getContestProblems($cid);

    public function getContestById($cid);

    public function getAllContestDetails();

    public function isContestByIdPublic($cid);
}
