<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRankRepository {

    public function getContestDetails($cid);
        
    public function getContestSolutions($cid);
    
    public function getFirstBlood($cid);
}
