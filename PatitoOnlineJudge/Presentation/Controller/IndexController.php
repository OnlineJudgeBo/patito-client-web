<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IStatusService;

class IndexController
{
    private $newsService;
    private $solutionService;
    private $statusService;
    public $title;

    public function __construct(INewsService $newsService, ISolutionService $solutionService, IStatusService $statusService)
    {
        $this->title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
        $this->statusService = $statusService;
    }

    public function render()
    {
        $title = $this->title;
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->statusService->getStatusData("");

        require_once __DIR__ . "/../Views/index.php";
    }
}
