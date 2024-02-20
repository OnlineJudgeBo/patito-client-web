<?php

namespace PatitoOnlineJudge\Presentation\Controller;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestRankService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IContestService;
use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IExcelService;

class ContestRankExcelController
{
    private $contestRankService;
    private $contestService;
    public $title;
    public $excelService;
    private $cid;

    public function __construct(IContestRankService $contestRankService, IContestService $contestService, IExcelService $excelService)
    {
        $this->title = "Contests";
        $this->contestRankService = $contestRankService;
        $this->contestService = $contestService;
        $this->excelService = $excelService;
    }

    public function addCid($cid)
    {
        $this->cid = $cid;
    }

    public function render()
    {
        $title = $this->title;
        $problems = $this->contestService->getContestProblems($this->cid);
        $contest = $this->contestService->getContestById($this->cid);
        $start_time = strtotime($contest["start_time"]);
        $end_time = strtotime($contest["end_time"]);
        $obi = 0;
        $contestRank = $this->contestRankService->getContestRankListById($this->cid, $start_time, $end_time);

        $sec2str = function ($sec) {
            return sprintf("%02d:%02d:%02d", $sec / 3600, $sec % 3600 / 60, $sec % 60);
        };

        $stylesH = array('border' => 'left,right,top,bottom', 'fill' => '#cff', 'font-size' => 11, 'font-style' => 'bold', 'halign' => 'center');
        $stylesB = array('border' => 'left,right,top,bottom', 'halign' => 'center');

        $contest_id = $contest["contest_id"];
        $title = strip_tags($this->closetags($contest["title"]));

        $titleHeader = array($contest_id . "   " . $title);
        $this->excelService->addFileName($contest_id . "   " . $title);
        $formatoTitulo = array('font-size' => 12, 'font-style' => 'bold', 'align' => 'center', 'halign' => 'center');
        $formatoBody = array('font-size' => 11, 'align' => 'center', 'halign' => 'center');

        $this->excelService->addRow('Hoja 1', array("Juez Virtual"), $formatoBody);
        $this->excelService->addRow('Hoja 1', $titleHeader, $formatoTitulo);
        $this->excelService->addRow('Hoja 1', array("Hora Inicio", $contest["start_time"]), $formatoBody);
        $this->excelService->addRow('Hoja 1', array("Hora Fin", $contest["end_time"]), $formatoBody);
        $this->excelService->addRow('Hoja 1', array(), $formatoTitulo);

        $this->excelService->markMergedCell('Hoja 1', 1, 0, 1, 12);
        $this->excelService->markMergedCell('Hoja 1', 2, 1, 2, 2);
        $this->excelService->markMergedCell('Hoja 1', 3, 1, 3, 2);

        $head = array(
            "#" => "integer",
            "NOMBRE" => "string",
            "APELLIDO" => "string",
            "USUARIO" => "string",
            "RESULTADOS" => "string",
            //"PENALIDAD" => "string",
        );

        require __DIR__ . "/../../../Legacy/Include/const.inc.php";
        foreach ($problems as $key => $value) {
            $head[$PID2[$key]] = "string";
        }

        $this->excelService->addHeader("Hoja 1", $head, $stylesH);

        foreach ($contestRank as $index => $row) {
            $data = array();
            $css = "oddrow";
            if ($index % 2 == 0) {
                $css = "evenrow";
            }
            array_push($data, ($index + 1));
            array_push($data, $row->nick);
            array_push($data, $row->lastname);
            array_push($data, $row->user_id);
            array_push($data, $row->solved);
            //array_push($data, $sec2str($row->time));


            for ($j = 0; $j < count($problems); $j++) {
                $element = "";
                if (isset($row)) {
                    if (
                        isset($row->p_ac_sec[$j]) &&
                        $row->p_ac_sec[$j] > 0
                    ) {
                        $element = $sec2str($row->p_ac_sec[$j]);
                    }
                    if ($obi == 1) {
                        if ($row->pass_rate[$j] > 0) {
                            $element = $element . " " . " (" . intval($row->pass_rate[$j]) . "%)";
                        } else {
                            if (
                                isset($row->p_wa_num[$j]) &&
                                $row->p_wa_num[$j] > 0
                            ) {
                                $element = $element . " " . "(-" . $row->p_wa_num[$j] . ")";
                            }
                        }
                    } else {
                        if (
                            isset($row->p_wa_num[$j]) &&
                            $row->p_wa_num[$j] > 0
                        ) {
                            $element = $element . " " . "(-" . $row->p_wa_num[$j] . ")";
                        }
                    }
                }
                array_push($data, $element);
            }
            $this->excelService->addRow("Hoja 1", $data, $stylesB);
        }

        $this->excelService->saveExcel();
    }

    public function closetags($html)
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];

        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}
