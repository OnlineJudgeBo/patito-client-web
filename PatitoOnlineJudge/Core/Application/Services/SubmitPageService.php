<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\ISubmitPageRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISubmitPageService;

class SubmitPageService implements ISubmitPageService
{
    protected $submitPageRepository;

    public function __construct(ISubmitPageRepository $submitPageRepository)
    {
        $this->submitPageRepository = $submitPageRepository;
    }

    public function saveContestRequest($pid, $cid, $source)
    {
    }

    public function saveProblemRequest($pid, $source)
    {
    }
}
