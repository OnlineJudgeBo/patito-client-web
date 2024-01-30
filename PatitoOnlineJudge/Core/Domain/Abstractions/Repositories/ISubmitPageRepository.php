<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface ISubmitPageRepository
{
    public function saveContestRequest($pid, $cid, $source);

    public function saveProblemRequest($pid, $source);
}
