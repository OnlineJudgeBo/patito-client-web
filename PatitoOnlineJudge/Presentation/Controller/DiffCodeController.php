<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IShowSourceService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class DiffCodeController
{
    public $title;
    public $solution_id = "";
    public $solution_id2 = "";
    private $showSourceService;

    public function __construct()
    {
        $this->title = "";
    }

    public function addService(IShowSourceService $showSourceService)
    {
        $this->showSourceService = $showSourceService;
    }

    public function setSolution1($sid)
    {
        $this->solution_id = $sid;
    }

    public function setSolution2($sid)
    {
        $this->solution_id2 = $sid;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $sourceDetail  = $this->showSourceService->showCode($this->solution_id);
        $sourceDetail2 = $this->showSourceService->showCode($this->solution_id2);
        require_once $current_theme . "/diffCode.php";
    }
}
