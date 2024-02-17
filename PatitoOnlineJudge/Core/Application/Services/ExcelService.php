<?php

namespace PatitoOnlineJudge\Core\Application\Services;

use PatitoOnlineJudge\Core\Domain\Abstractions\Services\IExcelService;

require __DIR__."/../../../Infraestructure/ExcelWriter/xlsxwriter.class.php";

class ExcelService implements IExcelService
{
    private $xlsxWriter;
    private $fileName;

    public function __construct()
    {
        $this->xlsxWriter = new \XLSXWriter();
        $this->xlsxWriter->setAuthor('Juez Virtual developed by Samuel Loza samuel.loza26@gmail.com');
        $keywords = array('Juez Virtual','Reporte','samuel.loza26@gmail.com');
        $this->xlsxWriter->setTitle('Reporte Juez Virtual <samuel.loza26@gmail.com>');
        $this->xlsxWriter->setSubject('Reporte Juez Virtual <samuel.loza26@gmail.com>');
        $this->xlsxWriter->setCompany('Reporte Juez Virtual <samuel.loza26@gmail.com>');
        $this->xlsxWriter->setKeywords($keywords);
        $this->xlsxWriter->setDescription('Reporte Juez Virtual <samuel.loza26@gmail.com>');
    }

    public function addFileName($fileName)
    {
        $this->fileName = preg_replace('/[^\w\-.]/', '', $fileName);;
    }

    public function addSheet($sheetName, $data)
    {
        $this->xlsxWriter->writeSheetHeader($sheetName, $data, ['freeze_rows'=>1, 'freeze_columns'=>1]);
    }

    public function addHeader($sheetName, $head, $style) {

        $this->xlsxWriter->writeSheetHeader($sheetName, $head, $style);
    }

    public function addRow($sheetName, $rowData, $style)
    {
        $this->xlsxWriter->writeSheetRow($sheetName, $rowData, $style);
    }

    public function markMergedCell($sheet1, $start_row, $start_col, $end_row, $end_col)
    {
        $this->xlsxWriter->markMergedCell($sheet1, $start_row, $start_col, $end_row, $end_col);
    }

    public function saveExcel()
    {
        header("Content-Type: text/html;charset=UTF-8");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: application/force-download");
        header('Content-Disposition: attachment; filename='.$this->fileName.'.xlsx');
        $this->xlsxWriter->writeToStdOut();
    }
}
