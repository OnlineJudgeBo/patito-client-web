<?php

namespace PatitoOnlineJudge\Presentation\Controller;

class FaqController
{
    public $title;

    public function __construct()
    {
        $this->title = "Preguntas Frecuentes";
    }

    public function render()
    {
        $title = $this->title;
        require_once __DIR__ . "/../Views/faq.php";
    }
}
