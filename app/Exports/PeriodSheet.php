<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PeriodSheet implements FromView, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    protected $tab;
    protected $items;

    public function __construct($tab, $items)
    {
        $this->tab = $tab;
        $this->items = $items;
    }

    public function view(): View
    {
        return view('exports.periodSheet', [
            'tab' => $this->tab,
            'items' => $this->items,
        ]);
    }

    public function title(): string
    {
        return $this->tab;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Freeze header
        $sheet->freezePane('A3');

        // Header
        $sheet->getStyle('A1:J2')->applyFromArray([
            'font' => [
                'size' => 12,
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // No
        $cellRange = 'A3:A' . $sheet->getHighestRow();
        $sheet->getStyle($cellRange)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
            ]
        ]);

        // Value
        $cellRange = 'B3:' . $sheet->getHighestColumn() . $sheet->getHighestRow();
        $sheet->getStyle($cellRange)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP,
            ]
        ]);
    }
}
