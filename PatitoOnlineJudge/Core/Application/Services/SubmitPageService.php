<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use Exception;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IProblemRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISourceCodeRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISubmitPageService;
use PatitoOnlineJudge\Infrastructure\Database\EntityObjects\SolutionModel;

class SubmitPageService implements ISubmitPageService
{
    private $submitPageRepository;
    private $sourceCodeRepository;
    private $contestService;
    private $problemRepository;
    private $site_id;

    public function __construct(
        ISubmitPageRepository $submitPageRepository,
        ISourceCodeRepository $sourceCodeRepository,
        IContestService $contestService,
        IProblemRepository $problemRepository
    ) {
        $this->submitPageRepository = $submitPageRepository;
        $this->sourceCodeRepository = $sourceCodeRepository;
        $this->problemRepository    = $problemRepository;
        $this->contestService       = $contestService;
        $this->site_id              = $_SERVER["SITE_ID"];
    }

    public function saveContestRequest($num, $cid, $source, $language_id)
    {
        $solutionModel = $this->createSolutionModel($cid, $num, $source, $language_id);

        if ($this->contestService->isVirtualContest($cid)) {
            $solution_id = $this->submitPageRepository->saveVirtualContestSolutionAndReturnId($solutionModel, $this->site_id);
        } elseif ($this->contestService->isContestActive($cid)) {
            $solution_id = $this->submitPageRepository->saveContestSolutionAndReturnId($solutionModel, $this->site_id);
        } else {
            throw new Exception("El contest no esta activo.");
        }
        $this->sourceCodeRepository->save($solution_id, $source);
    }

    public function saveProblemRequest($pid, $source, $language_id)
    {
        if ($this->problemRepository->isProblemInContest($pid, $this->site_id)) {
            throw new Exception("Actualmente, el problema {$pid} no se puede enviar porque está siendo utilizado en un contest. Para subir su solución entre al contest y envié desde allí.");
        }

        $solutionModel = $this->createSolutionModel(null, -1, $source, $language_id);
        $solutionModel->problem_id = $pid;

        $solution_id = $this->submitPageRepository->saveSolutionAndReturnId($solutionModel, $this->site_id);
        $this->sourceCodeRepository->save($solution_id, $source);
    }

    private function createSolutionModel($contest_id, $num, $source, $language_id)
    {
        $solutionModel = new SolutionModel();
        $solutionModel->language = $language_id;
        $solutionModel->code_length = strlen($source);
        $solutionModel->contest_id = $contest_id;
        $solutionModel->user_id = $_SESSION["user_id"];
        $solutionModel->ip = $_SERVER['REMOTE_ADDR'];
        $solutionModel->num = $num;

        if ($contest_id !== null) {
            $solutionModel->problem_id = $this->contestService->getProblemIdByNum($contest_id, $num);
        }

        return $solutionModel;
    }
}
