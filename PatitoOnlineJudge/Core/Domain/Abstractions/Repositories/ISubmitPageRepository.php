<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

use PatitoOnlineJudge\Infrastructure\Database\EntityObjects\SolutionModel;

interface ISubmitPageRepository
{
    public function saveSolutionAndReturnId(SolutionModel $solutionModel, $site_id);

    public function saveContestSolutionAndReturnId(SolutionModel $solutionModel, $site_id);

    public function saveVirtualContestSolutionAndReturnId(SolutionModel $solutionModel, $site_id);

    public function saveAcademicSolutionAndReturnId(SolutionModel $solutionModel, $siteId, $courseId, $assignmentId);
}
