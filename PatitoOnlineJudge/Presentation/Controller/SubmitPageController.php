<?php

namespace PatitoOnlineJudge\Presentation\Controller;

class SubmitPageController
{
    private $submitPageService;
    private $cid;
    private $pid;
    private $source;
    public $view_title;

    public function __construct($submitPageService)
    {
        $this->view_title = "Bienvenido al Juez de la Carrera de Informatica - UMSA";
        $this->submitPageService = $submitPageService;
        $this->cid = 0;
        $this->pid = 0;
    }

    public function addCid($cid)
    {
        $this->cid = $cid;
    }

    public function addPid($pid)
    {
        $this->pid = $pid;
    }

    public function addSource($source)
    {
        $this->source = $source;
    }

    public function saveRequest()
    {
        try {
            if ($this->pid > 0 && $this->cid > 0 ) {
                $this->submitPageService->saveContestRequest($this->pid, $this->cid, $this->source);
            } else {
                $this->submitPageService->saveProblemRequest($this->pid, $this->source);
            }
        } catch (\Exception $e) {
        }
    }

    public function render()
    {
        $OJ_LANGMASK = 32692;
        $id = $this->pid;
        $cid = $this->cid;

        require_once __DIR__ . "/../Views/submitpage.php";
    }
}
