<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface IShowSourceRepository
{
    public function showCode($solution_id, $user_id, $isAdmin);
}
