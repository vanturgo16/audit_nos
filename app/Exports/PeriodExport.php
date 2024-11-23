<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;


class PeriodExport implements WithMultipleSheets
{
    use Exportable;
    protected $dataCheck;

    public function __construct($dataCheck)
    {
        $this->dataCheck = $dataCheck;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->dataCheck as $tab => $items) {
            $sheets[] = new PeriodSheet($tab, $items);
        }
        return $sheets;
    }
}
