<?php

namespace PatitoOnlineJudge\Controller;

use PatitoOnlineJudge\Service\NewsService;
use PatitoOnlineJudge\Service\SolutionService;

class IndexController
{
    private $newsService;
    private $solutionService;
    public $view_title;

    public function __construct(NewsService $newsService, SolutionService $solutionService)
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
    }

    public function render()
    {
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->solutionService->getLastRuns();

        require_once __DIR__ . "/../Presentation/index.php";
    }
}
