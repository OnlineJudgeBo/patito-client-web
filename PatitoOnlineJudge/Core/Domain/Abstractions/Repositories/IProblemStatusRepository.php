<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IProblemStatusRepository
{
    public function getTopUsersByProblem($problem_id);
}
