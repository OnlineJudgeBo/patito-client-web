<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISourceCodeRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISubmitPageService;
use PatitoOnlineJudge\Infraestructure\Database\EntityObjects\SolutionModel;

class SubmitPageService implements ISubmitPageService
{
    private $submitPageRepository;
    private $sourceCodeRepository;
    private $contestService;

    public function __construct(ISubmitPageRepository $submitPageRepository, ISourceCodeRepository $sourceCodeRepository, IContestService $contestService)
    {
        $this->submitPageRepository = $submitPageRepository;
        $this->sourceCodeRepository = $sourceCodeRepository;
        $this->contestService       = $contestService;
    }

    public function saveContestRequest($num, $cid, $source, $language_id)
    {
        $solutionModel = new SolutionModel();
        $solutionModel->language = $language_id;
        $solutionModel->code_length = strlen($source);
        $solutionModel->problem_id = $this->contestService->getProblemIdByNum($cid, $num);
        $solutionModel->contest_id = $cid;
        $solutionModel->user_id = $_SESSION["user_id"];
        $solutionModel->ip = $_SERVER['REMOTE_ADDR'];
        $solutionModel->num = $num;
        $solution_id = $this->submitPageRepository->saveContestSolutionAndReturnId($solutionModel);
        $this->sourceCodeRepository->save($solution_id, $source);
    }

    public function saveProblemRequest($pid, $source, $language_id)
    {
        $solutionModel = new SolutionModel();
        $solutionModel->language = $language_id;
        $solutionModel->code_length = strlen($source);
        $solutionModel->problem_id = $pid;
        $solutionModel->user_id = $_SESSION["user_id"];
        $solutionModel->ip = $_SERVER['REMOTE_ADDR'];
        $solutionModel->num = -1;
        $solution_id = $this->submitPageRepository->saveSolutionAndReturnId($solutionModel);
        $this->sourceCodeRepository->save($solution_id, $source);
    }
}
