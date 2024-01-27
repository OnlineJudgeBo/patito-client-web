<?php

namespace PatitoOnlineJudge\Controller;

class SubmitPageController
{
    private $newsService;
    private $solutionService;
    public $view_title;

    public function __construct()
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
    }

    public function render()
    {
        require_once __DIR__ . "/../../resources/View/submitpage.php";
    }
}
