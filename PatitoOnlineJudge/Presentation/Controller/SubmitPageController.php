<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\ISubmitPageService;
use PatitoOnlineJudge\Presentation\Utils\Utils;

class SubmitPageController
{
    private $submitPageService;
    private $cid;
    private $pid;
    private $source;
    private $language_id;
    public $title;
    public $contestService;

    public function __construct(ISubmitPageService $submitPageService)
    {
        $this->title = "Bienvenido al Juez Virtual";
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

    public function addLanguage($language_id)
    {
        $this->language_id = $language_id;
    }

    public function addContestService(IContestService $contestService)
    {
        $this->contestService = $contestService;
    }

    public function saveRequest()
    {
        if ($this->cid > 0) {
            $this->submitPageService->saveContestRequest($this->pid, $this->cid, $this->source, $this->language_id);
            header("Location: status.php?cid=".$this->cid."&user_id=".$_SESSION["user_id"]);
        } else {
            try {
                $this->submitPageService->saveProblemRequest($this->pid, $this->source, $this->language_id);
            } catch (\Exception $e) {
                $current_theme = Utils::get_current_theme();
                $error = $e->getMessage();
                require_once $current_theme."/genericError.php";
                die();
            }
            header("Location: status.php");
        }
    }

    public function render()
    {
        $current_theme = Utils::get_current_theme();
        $title = $this->title;
        $OJ_LANGMASK = 32692;
        $id = "";
        $cid = "";
        $pid = "";
        $language_id = "";
        $problemName = "";
        $pid = $this->pid;
        $id = $this->pid;
        if (!empty($this->cid)) {
            $languagesAvailable = $this->contestService->languagesAvailable($this->cid);
            $cid = $this->cid;
            $id = $this->pid;
            $problemName = $this->contestService->getProblemTitleByNumber($cid, $pid);
            $PID = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ", "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ");
            $problemName = $PID[$pid] . " --> " . $problemName;
        } else {
            $languagesAvailable = $this->contestService->languagesAvailable(0);
        }
        
        require_once $current_theme . "/submitpage.php";
    }
}
