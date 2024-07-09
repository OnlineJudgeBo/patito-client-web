<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Infrastructure\Database\EntityObjects\SolutionModel;

interface ISubmitPageRepository
{
    public function saveSolutionAndReturnId(SolutionModel $solutionModel);

    public function saveContestSolutionAndReturnId(SolutionModel $solutionModel);

    public function saveVirtualContestSolutionAndReturnId(SolutionModel $solutionModel);
}
