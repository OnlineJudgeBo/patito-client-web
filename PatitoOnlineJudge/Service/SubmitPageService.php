<?php

namespace PatitoOnlineJudge\Service;

use PatitoOnlineJudge\Repository\SubmitPageRepository;

class SubmitPageService
{
    protected $submitPageRepository;

    public function __construct(SubmitPageRepository $submitPageRepository)
    {
        $this->submitPageRepository = $submitPageRepository;
    }
}
