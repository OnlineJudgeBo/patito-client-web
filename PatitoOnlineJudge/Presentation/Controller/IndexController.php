<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class IndexController
{
    private $newsService;
    private $solutionService;
    public $title;

    public function __construct(INewsService $newsService, ISolutionService $solutionService)
    {
        $this->title = "Bienvenido al Juez de la Carrera de Informática - UMSA";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->solutionService->getStatusData("", 50);

        require_once $current_theme . "/index.php";
    }
}
