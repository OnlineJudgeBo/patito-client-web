<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IuserStaticRepository
{

    public function getTotalUserSubmitByProblem($problem_id, $site_id);

    public function getTotalUserAcByProblem($problem_id, $site_id);

    public function getTotalUserPeByProblem($problem_id, $site_id);

    public function getTotalUserWaByProblem($problem_id, $site_id);

    public function getTotalUserTleByProblem($problem_id, $site_id);

    public function getTotalUserOleByProblem($problem_id, $site_id);

    public function getTotalUserReByProblem($problem_id, $site_id);

    public function getTotalUserCeByProblem($problem_id, $site_id);
}
