<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;

class IndexController
{
    private $newsService;
    private $solutionService;
    public $title;

    public function __construct(INewsService $newsService, ISolutionService $solutionService)
    {
        $this->title = "Bienvenido al Juez Virtual Bo";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
    }

    public function render()
    {
        $title = $this->title;
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->solutionService->getStatusData("", 50);

        require_once __DIR__ . "/../Views/index.php";
    }
}
