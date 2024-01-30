<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface ISourceCodeRepository
{
    public function save($solution_id, $source);
}
