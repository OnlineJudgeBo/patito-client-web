<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IStatusService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class StatusController
{
    private $statusService;
    public $title;
    public $params;

    public function __construct(ISolutionService $solutionService)
    {
        $this->title = "Envíos";
        $this->statusService = $solutionService;
    }

    public function add_params($key, $param)
    {
        $this->params[$key] = $param;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $statusViewList = $this->statusService->getStatusData($this->params, -1);
        if (isset($this->params["contest_id"])) {
            $cid = $this->params["contest_id"];
        }
        require_once $current_theme . "/status.php";
    }
}
