<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Repositories;

interface ILoginRepository {

    public function getUser($username);

    public function getPrivilege($username);

    public function updateUserLastLogin($username, $accesstime);

    public function logLoginAttempt($username);
}
