<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IContestRankService
{
    function getContestRankListById($cid, $start_time, $end_time);
    public function getFirstBlood($cid);
}
