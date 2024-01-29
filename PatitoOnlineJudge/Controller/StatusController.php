<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\StatusService;

class StatusController
{
    private $statusService;
    public $view_title;
    public $params;

    public function __construct(StatusService $statusService)
    {
        $this->view_title = "Envios";
        $this->statusService = $statusService;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function render()
    {
        $statusViewList = $this->statusService->getStatusData($this->params);
        require_once __DIR__ . "/../Presentation//status.php";
    }
}
