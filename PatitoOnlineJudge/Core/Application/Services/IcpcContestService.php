<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IIcpcContestRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IIcpcContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISessionService;

class IcpcContestService implements IIcpcContestService
{
    protected $icpcContestRepository;
    private $sessionService;

    public function __construct(ISessionService $sessionService, IIcpcContestRepository $icpcContestRepository)
    {
        $this->icpcContestRepository = $icpcContestRepository;
        $this->sessionService = $sessionService;
    }

    public function isContestActive($cid)
    {
        return $this->icpcContestRepository->isContestActive($cid);
    }

    public function getContestProblems($cid)
    {
        return $this->icpcContestRepository->getProblemsByContestId($cid);
    }

    public function getContestById($cid)
    {
        return $this->icpcContestRepository->getContestById($cid);
    }

    public function getAllContestDetails()
    {
        $role = $this->sessionService->getUserRole();

        if ($role === 'Administrador') {
            return $this->icpcContestRepository->getAllContestsForAdmin();
        } elseif ($role === 'Docente' || $role === 'Auxiliar') {
            return $this->icpcContestRepository->getAllContests();
        } else {
            return $this->icpcContestRepository->getAllContests();
        }
    }

    public function isContestByIdPublic($cid)
    {
        return $this->icpcContestRepository->isContestByIdPublic($cid);
    }

    public function getAcProblemsByIdContest($cid)
    {
        $data = $this->icpcContestRepository->getAcProblemsByIdContest($cid);
        $result = array();
        foreach ($data as $value) {
            $result[$value["user_id"]][] = $value["num"];
        }
        return $result;
    }

    public function getProblemTitleByNumber($cid, $num)
    {
        return $this->icpcContestRepository->getProblemTitleByNumber($cid, $num);
    }

    public function getProblemIdByNum($cid, $pid)
    {
        return $this->icpcContestRepository->getProblemIdByNum($cid, $pid);
    }

    public function languagesAvailable($cid)
    {
        if ($cid > 0) {
            return $this->icpcContestRepository->getLanguagesAvailable($cid);
        } else {
            return $this->icpcContestRepository->getAllLanguages();
        }
    }
}
