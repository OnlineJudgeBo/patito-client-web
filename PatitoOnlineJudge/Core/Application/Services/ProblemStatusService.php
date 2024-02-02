<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemStatusRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IuserStaticRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemStatusService;

class ProblemStatusService implements IProblemStatusService
{
    private $problemStatusRepository;
    private $userStaticRepository;

    public function __construct(IProblemStatusRepository $problemStatusRepository, IuserStaticRepository $userStaticRepository)
    {
        $this->problemStatusRepository = $problemStatusRepository;
        $this->userStaticRepository = $userStaticRepository;
    }

    public function getUserStatics($params)
    {
        $totalsubmits = $this->userStaticRepository->getTotalUserSubmitByProblem(1000);
        $totalac = $this->userStaticRepository->getTotalUserAcByProblem(1000);
        $totalpe = $this->userStaticRepository->getTotalUserPeByProblem(1000);
        $totalwa = $this->userStaticRepository->getTotalUserWaByProblem(1000);
        $totaltle = $this->userStaticRepository->getTotalUserTleByProblem(1000);
        $totalole = $this->userStaticRepository->getTotalUserOleByProblem(1000);
        $totalre = $this->userStaticRepository->getTotalUserReByProblem(1000);
        $totalce = $this->userStaticRepository->getTotalUserCeByProblem(1000);

        return  [
            'total_submits' => $totalsubmits,
            'total_ac' => $totalac,
            'total_pe' => $totalpe,
            'total_wa' => $totalwa,
            'total_tle' => $totaltle,
            'total_ole' => $totalole,
            'total_re' => $totalre,
            'total_ce' => $totalce,
        ];
    }

    public function getTopUsersByProblem($problem_id)
    {
        return $this->problemStatusRepository->getTopUsersByProblem($problem_id);
    }
}
