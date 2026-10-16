<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\INewsService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IScheduleService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class IndexController
{
    private $newsService;
    private $scheduleService;
    public $title;

    public function __construct(INewsService $newsService, IScheduleService $scheduleService)
    {
        $this->title = "Bienvenido al Juez de la Carrera de Informática - UMSA";
        $this->newsService = $newsService;
        $this->scheduleService = $scheduleService;
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $view_news = $this->newsService->getLatestNews();
        if ($_SERVER["THEME_TEMPLATE"] == "patito") {
            $schedule = $this->scheduleService->getSchedule();
        }
        require_once $current_theme . "/index.php";
    }
}
