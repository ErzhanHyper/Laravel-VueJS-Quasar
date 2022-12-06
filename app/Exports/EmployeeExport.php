<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeExport implements FromCollection, WithHeadings, WithHeadingRow
{

    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'ФИО',
            'Телефон',
            'E-mail',
            'Должность',
            'Департамент',
            'Кабинет',
            'Внутренний номер',
            'Статус'
        ];
    }

    public function collection()
    {
        $items = [];
        $employee = Employee::all();

        foreach ($employee as $item) {
            $items[] = [
                'fullname' => $item->lastname . ' ' . $item->firstname . ' ' . $item->middlename,
                'phone' => $item->phone,
                'email' => $item->email,
                'profession' => $item->profession->name,
                'department' => $item->department->name,
                'cabinet' => $item->cabinet,
                'extension' => $item->extension,
                'status' => $item->employee_status->name,
            ];
        }
        return collect($items);
    }
}
