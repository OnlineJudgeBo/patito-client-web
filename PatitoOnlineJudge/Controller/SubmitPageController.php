<?php

namespace PatitoOnlineJudge\Controller;

class SubmitPageController
{
    private $submitPageService;
    private $loginService;
    private $cid;
    private $pid;
    public $view_title;

    public function __construct($submitPageService, $loginService, $pid, $cid)
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->submitPageService = $submitPageService;
        $this->loginService = $loginService;
        $this->pid = $pid;
        $this->cid = $cid;
    }

    public function render()
    {
        $OJ_LANGMASK = 32692;
        $id = $this->pid;
        $cid = $this->cid;

        require_once __DIR__ . "/../Presentation/submitpage.php";
    }
}
