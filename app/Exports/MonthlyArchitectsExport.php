<?php

namespace App\Exports;

use App\Models\Architect;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * @implements WithMapping<mixed>
 */
class MonthlyArchitectsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles
{
    public function __construct(protected int $month, protected int $year) {}

    /** */
    #[\Override]
    public function collection()
    {
        return Architect::query()
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /** */
    #[\Override]
    public function headings(): array
    {
        return [
            'ID',
            'Architect Name',
            'Representative',
            'Type',
            'Class ID',
            'Created At',
        ];
    }

    /**
     *
     * @return array
     */
    #[\Override]
    public function map(mixed $row): array
    {
        return [
            $row->id,
            $row->architect_name,
            $row->architectRep->name,
            $row->architectType->architect_type_desc,
            $row->class_id ?? 'N/A',
            $row->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /** */
    #[\Override]
    public function title(): string
    {
        $title = Carbon::create($this->year, $this->month);

        if ($title) {
            return $title->format('F Y');
        } else {
            return "New Architect Last Month";
        }
    }

    /** */
    #[\Override]
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
