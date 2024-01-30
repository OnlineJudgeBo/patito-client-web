<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class IndexController
{
    private $newsService;
    private $solutionService;
    public $view_title;

    public function __construct(INewsService $newsService, ISolutionService $solutionService)
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
    }

    public function render()
    {
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->solutionService->getLastRuns();

        require_once __DIR__ . "/../Views/index.php";
    }
}
