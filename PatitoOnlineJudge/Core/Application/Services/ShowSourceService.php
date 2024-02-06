<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Repositories\IShowSourceRepository;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IShowSourceService;

class ShowSourceService implements IShowSourceService
{
    private $showSourceRepository;

    public function __construct(IShowSourceRepository $showSourceRepository)
    {
        $this->showSourceRepository = $showSourceRepository;
    }

    public function showCode($id)
    {
        $user_id = $_SESSION['user_id'];
        $isAdmin = false;
        if (isset($_SESSION["administrator"])) {
            $isAdmin = true;
        }
        return $this->showSourceRepository->showCode($id, $user_id, $isAdmin);
    }
}
