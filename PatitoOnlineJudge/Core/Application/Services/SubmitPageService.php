<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISourceCodeRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISubmitPageService;
use PatitoOnlineJudge\Infraestructure\Database\EntityObjects\SolutionModel;

class SubmitPageService implements ISubmitPageService
{
    private $submitPageRepository;
    private $sourceCodeRepository;

    public function __construct(ISubmitPageRepository $submitPageRepository, ISourceCodeRepository $sourceCodeRepository)
    {
        $this->submitPageRepository = $submitPageRepository;
        $this->sourceCodeRepository = $sourceCodeRepository;
    }

    public function saveContestRequest($pid, $cid, $source, $language_id)
    {
        $solutionModel = new SolutionModel();
        $solutionModel->language = $language_id;
        $solutionModel->code_length = strlen($source);
        $solutionModel->problem_id = $pid;
        $solutionModel->contest_id = $cid;
        $solutionModel->user_id = $_SESSION["user_id"];
        $solutionModel->ip = $_SERVER['REMOTE_ADDR'];
        $solutionModel->num = $pid;
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
