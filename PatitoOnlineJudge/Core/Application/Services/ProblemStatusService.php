<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemStatusRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IuserStaticRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IProblemStatusService;

class ProblemStatusService implements IProblemStatusService
{
    private $problemStatusRepository;
    private $userStaticRepository;
    private $site_id;

    public function __construct(IProblemStatusRepository $problemStatusRepository, IuserStaticRepository $userStaticRepository)
    {
        $this->problemStatusRepository = $problemStatusRepository;
        $this->userStaticRepository = $userStaticRepository;
        $this->site_id = $_SERVER["SITE_ID"];
    }

    public function getUserStatics($problemId)
    {
        $totalsubmits = $this->userStaticRepository->getTotalUserSubmitByProblem($problemId, $this->site_id);
        $totalac = $this->userStaticRepository->getTotalUserAcByProblem($problemId, $this->site_id);
        $totalpe = $this->userStaticRepository->getTotalUserPeByProblem($problemId, $this->site_id);
        $totalwa = $this->userStaticRepository->getTotalUserWaByProblem($problemId, $this->site_id);
        $totaltle = $this->userStaticRepository->getTotalUserTleByProblem($problemId, $this->site_id);
        $totalole = $this->userStaticRepository->getTotalUserOleByProblem($problemId, $this->site_id);
        $totalre = $this->userStaticRepository->getTotalUserReByProblem($problemId, $this->site_id);
        $totalce = $this->userStaticRepository->getTotalUserCeByProblem($problemId, $this->site_id);

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
        return $this->problemStatusRepository->getTopUsersByProblem($problem_id, $this->site_id);
    }
}
