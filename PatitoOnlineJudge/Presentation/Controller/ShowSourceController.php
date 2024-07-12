<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IShowSourceService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class ShowSourceController
{
    private $showSourceService;
    public $title;
    public $solution_id;

    public function __construct(IShowSourceService $showSourceService)
    {
        $this->title = "Envios";
        $this->showSourceService = $showSourceService;
    }

    public function addSolutionId($solution_id)
    {
        $this->solution_id = $solution_id;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $sourceDetail = $this->showSourceService->showCode($this->solution_id);
        require_once $current_theme . "/showsource.php";
    }
}
