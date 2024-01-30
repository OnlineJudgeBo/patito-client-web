<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ISubmitPageService
{
    public function saveContestRequest($pid, $cid, $source);

    public function saveProblemRequest($pid, $source);
}
