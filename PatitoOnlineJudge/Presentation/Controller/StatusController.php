<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IStatusService;

class StatusController
{
    private $statusService;
    public $view_title;
    public $params;

    public function __construct(IStatusService $statusService)
    {
        $this->view_title = "Envios";
        $this->statusService = $statusService;
    }

    public function add_params($key, $param)
    {
        $this->params[$key] = $param;
    }

    public function render()
    {
        $statusViewList = $this->statusService->getStatusData($this->params);
        require_once __DIR__ . "/../Views//status.php";
    }
}
