<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface ISubmitPageService
{
    public function saveContestRequest($pid, $cid, $source, $language_id);

    public function saveProblemRequest($pid, $source, $language_id);
}
