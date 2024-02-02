<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;


interface IProblemStatusService
{
    public function getUserStatics($problem_id);
    public function getTopUsersByProblem($problem_id);
}
