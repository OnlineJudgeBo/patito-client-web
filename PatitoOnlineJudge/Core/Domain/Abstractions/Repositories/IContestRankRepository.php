<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRankRepository {

    public function getContestDetails($cid);
        
    public function getContestSolutions($cid, $realm);
    
    public function getFirstBlood($cid);
}
