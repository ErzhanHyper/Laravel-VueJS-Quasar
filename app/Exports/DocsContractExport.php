<?php

namespace App\Exports;

use App\Models\DocsContract;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocsContractExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Реестр договоров';
    }

    public function headings(): array
    {
        return [
            ['Реестр договоров'],
            [
                'Номер договора',
                'Наименование',
                'Контрагент',
                'Сумма',
                'Дата',
                'Срок действия'
            ]
        ];
    }

    public function collection()
    {
        $items = [];
        $contract = DocsContract::with('agent');

        if ($this->data->contract_id) {
            $contract = DocsContract::where('contract_id', $this->data->contract_id);
        }
        if ($this->data->agent) {
            $contract = DocsContract::where('agent_id', $this->data->agent);
        }
        if ($this->data->date) {
            $contract = DocsContract::where('date', Carbon::parse($this->data->date)->timestamp);
        }

        foreach ($contract->get() as $item) {

            $items[] = [
                '0' => '№ ' . $item->contract_id,
                '1' => $item->name,
                '2' => $item->agent->name . ' | ' . $item->agent->bin,
                '3' => $item->amount,
                '4' => Carbon::parse($item->date)->format('Y-m-d'),
                '5' => 'c ' . Carbon::parse($item->date_start)->format('Y-m-d') . ' по ' . Carbon::parse($item->date_end)->format('Y-m-d'),
            ];
        }

        return collect($items);
    }
}
