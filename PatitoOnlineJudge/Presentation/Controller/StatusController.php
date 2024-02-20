<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IStatusService;

class StatusController
{
    private $statusService;
    public $title;
    public $params;

    public function __construct(IStatusService $statusService)
    {
        $this->title = "Envios";
        $this->statusService = $statusService;
    }

    public function add_params($key, $param)
    {
        $this->params[$key] = $param;
    }

    public function render()
    {
        $title = $this->title;
        $statusViewList = $this->statusService->getStatusData($this->params);
        if (isset($this->params["contest_id"])) {
            $cid = $this->params["contest_id"];
        }
        require_once __DIR__ . "/../Views/status.php";
    }
}
