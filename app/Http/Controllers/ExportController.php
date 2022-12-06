<?php

namespace App\Http\Controllers;

use App\Exports\DocsContractExport;
use App\Exports\EmployeeExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function employee_export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new EmployeeExport($request), 'employee.xlsx');
    }

    public function docs_contract_export(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new DocsContractExport($request), 'contracts.xlsx');
    }
}
