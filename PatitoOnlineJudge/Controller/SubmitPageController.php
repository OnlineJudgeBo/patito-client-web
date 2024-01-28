<?php

namespace PatitoOnlineJudge\Controller;

class SubmitPageController
{
    private $submitPageService;
    private $loginService;
    private $cid;
    private $pid;
    public $view_title;

    public function __construct($submitPageService, $loginService, $cid, $pid)
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->submitPageService = $submitPageService;
        $this->loginService = $loginService;
        $this->cid = $cid;
        $this->pid = $pid;
    }

    public function render()
    {
        $OJ_LANGMASK = 32692;
        $id = $this->pid;
        $cid = $this->cid;

        if (isset($_GET["id"])) {
            $id = intval($_GET["id"]);
        }

        if (isset($_GET["cid"])) {
            $cid = intval($_GET["cid"]);
        }

        require_once __DIR__ . "/../../resources/View/submitpage.php";
    }
}
