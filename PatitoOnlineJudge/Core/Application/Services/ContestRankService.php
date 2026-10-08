<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRankRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestRankService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\ScoreDomainObject;

class ContestRankService implements IContestRankService
{
    private $contestRankRepository;
    private $site_id;

    public function __construct(IContestRankRepository $contestRankRepository)
    {
        $this->contestRankRepository = $contestRankRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function getFirstBlood($cid)
    {
        return $this->contestRankRepository->getFirstBlood($cid, $this->site_id);
    }

    public function getContestRankListById($cid, $start_time, $end_time, $obi = 0)
    {
        $rows = $this->contestRankRepository->getContestSolutions($cid, $this->site_id );
        $user_cnt = 0;
        $user_name = '';
        $U = array();
        $OJ_RANK_LOCK_PERCENT = 0;
        $lock = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;
        foreach ($rows as $row) {
            $n_user = $row['user_id'];
            if (strcmp($user_name, $n_user)) {
                $user_cnt++;
                $U[$user_cnt] = new ScoreDomainObject();

                $U[$user_cnt]->user_id = $row['user_id'];
                $U[$user_cnt]->nick = $row['nick'];
                $U[$user_cnt]->lastname = $row['lastname'];

                $user_name = $n_user;
            }
            $sec = strtotime($row['in_date']) - $start_time;
            $frozen = time() < $end_time && $lock < strtotime($row['in_date']);
            if ($obi == 1) {
                // A frozen submission counts as sent but shows no score yet.
                $U[$user_cnt]->AddPoints($row['num'], $sec, $frozen ? 0 : $row['pass_rate'], $row['is_virtual']);
            } else {
                $U[$user_cnt]->Add($row['num'], $sec, $frozen ? 0 : intval($row['result']), 0, 0, $row['is_virtual']);
            }
        }
        
        $scoreDomainObject = new ScoreDomainObject();
        if ($obi == 1) {
            usort($U, [$scoreDomainObject, "points_cmp"]);
        } else {
            usort($U, [$scoreDomainObject, "s_cmp"]);
        }
        return $U;
    }
}
