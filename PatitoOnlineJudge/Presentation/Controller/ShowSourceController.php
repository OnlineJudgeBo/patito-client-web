<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IShowSourceService;

class ShowSourceController
{
    private $showSourceService;
    public $view_title;
    public $solution_id;

    public function __construct(IShowSourceService $showSourceService)
    {
        $this->view_title = "Envios";
        $this->showSourceService = $showSourceService;
    }

    public function addSolutionId($solution_id)
    {
        $this->solution_id = $solution_id;
    }

    public function render()
    {
        $sourceDetail = $this->showSourceService->showCode($this->solution_id);
        require_once __DIR__ . "/../Views/showsource.php";
    }
}
