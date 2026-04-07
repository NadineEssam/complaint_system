<?php

namespace App\Exports;

use App\Reports\Contracts\ReportInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
{

    protected Collection $data;

    public function __construct(
        protected ReportInterface $report,
        array $filters
    ) {
        $this->data = collect($report->generate($filters));
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->report->headings();
    }

    public function map($row): array
    {
        return $this->report->map($row);
    }

    public function title(): string
    {
        return $this->report->label();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Bold + background on heading row
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => [
                    'fillType'   => 'solid',
                    'startColor' => ['argb' => 'FF1E3A5F'],
                ],
            ],
        ];
    }
}
