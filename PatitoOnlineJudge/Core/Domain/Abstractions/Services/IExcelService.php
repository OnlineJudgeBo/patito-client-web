<?php

namespace PatitoOnlineJudge\Core\Domain\Abstractions\Services;

interface IExcelService
{
    public function addFileName($fileName);
    
    public function addHeader($sheetName, $head, $style);

    public function addSheet($sheetName, $data);

    public function addRow($sheetName, $rowData, $style);

    public function markMergedCell($sheet1, $start_row, $start_col, $end_row, $end_col);

    public function saveExcel();
}
