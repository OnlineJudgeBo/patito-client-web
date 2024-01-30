<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Infraestructure\Database\EntityObjects\SolutionModel;

interface ISubmitPageRepository
{
    public function saveSolutionAndReturnId(SolutionModel $solutionModel);

    public function saveContestSolution(SolutionModel $solutionModel);
}
