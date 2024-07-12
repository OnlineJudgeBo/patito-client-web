<?php

namespace PatitoOnlineJudge\Presentation\Controller;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class FaqController
{
    public $title;

    public function __construct()
    {
        $this->title = "Preguntas Frecuentes";
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        require_once $current_theme . "/faq.php";
    }
}
