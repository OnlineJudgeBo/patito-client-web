<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IUserInfoRepository
{

    public function listProblemRepository($userId);
}
