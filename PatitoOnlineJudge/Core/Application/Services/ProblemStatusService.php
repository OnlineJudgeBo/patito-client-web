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

    public function getUserStatics($problemId)
    {
        $totalsubmits = $this->userStaticRepository->getTotalUserSubmitByProblem($problemId);
        $totalac = $this->userStaticRepository->getTotalUserAcByProblem($problemId);
        $totalpe = $this->userStaticRepository->getTotalUserPeByProblem($problemId);
        $totalwa = $this->userStaticRepository->getTotalUserWaByProblem($problemId);
        $totaltle = $this->userStaticRepository->getTotalUserTleByProblem($problemId);
        $totalole = $this->userStaticRepository->getTotalUserOleByProblem($problemId);
        $totalre = $this->userStaticRepository->getTotalUserReByProblem($problemId);
        $totalce = $this->userStaticRepository->getTotalUserCeByProblem($problemId);

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
