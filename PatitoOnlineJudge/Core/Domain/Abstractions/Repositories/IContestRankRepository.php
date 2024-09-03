<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IContestRankRepository {

    public function getContestDetails($cid);
        
    public function getContestSolutions($cid, $site_id);
    
    public function getFirstBlood($cid, $site_id);
}
