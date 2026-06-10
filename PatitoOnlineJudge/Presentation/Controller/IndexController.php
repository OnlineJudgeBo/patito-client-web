<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IScheduleService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISolutionService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class IndexController
{
    private $newsService;
    private $solutionService;
    private $scheduleService;
    public $title;

    public function __construct(INewsService $newsService, ISolutionService $solutionService, IScheduleService $scheduleService)
    {
        $this->title = "Bienvenido al Juez de la Carrera de Informática - UMSA";
        $this->newsService = $newsService;
        $this->solutionService = $solutionService;
        $this->scheduleService = $scheduleService;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $view_news = $this->newsService->getLatestNews();
        $view_last_runs = $this->solutionService->getStatusData("", 50);
        if ($_SERVER["THEME_TEMPLATE"] == "patito") {
            $schedule = $this->scheduleService->getSchedule();
        }
        require_once $current_theme . "/index.php";
    }
}
