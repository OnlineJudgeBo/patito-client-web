<?php

namespace PatitoOnlineJudge\Presentation\Controller;

class FaqController
{
    public $view_title;

    public function __construct()
    {
        $this->view_title = "Preguntas Frecuentes";
    }

    public function render()
    {
        require_once __DIR__ . "/../Views/faq.php";
    }
}
