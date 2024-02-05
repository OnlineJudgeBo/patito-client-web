<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IContestRankRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestRankService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\DomainObjects\ScoreDomainObject;

class ContestRankService implements IContestRankService
{
    private $contestRankRepository;

    public function __construct(IContestRankRepository $contestRankRepository)
    {
        $this->contestRankRepository = $contestRankRepository;
    }

    public function getFirstBlood($cid)
    {
        return $this->contestRankRepository->getFirstBlood($cid);
    }

    public function getContestRankListById($cid, $start_time, $end_time)
    {
        $rows = $this->contestRankRepository->getContestSolutions($cid);
        $obi = 0;
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

                $user_name = $n_user;
            }
            if (time() < $end_time && $lock < strtotime($row['in_date'])) {
                if ($obi == 1) {
                    $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 0, 0, 1);
                } else {
                    $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 0, 0, 0);
                }
            } else {
                if ($obi == 1) {
                    if ($row['pass_rate'] > 0.0) {
                        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 4, $row['pass_rate'], 1);
                    } else {
                        $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, intval($row['result']), 0, 1);
                    }
                } else {
                    $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, intval($row['result']), 0);
                }
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
