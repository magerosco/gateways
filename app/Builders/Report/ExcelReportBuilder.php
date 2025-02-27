<?php

namespace App\Builders\Report;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ExcelReportBuilder extends ReportBuilder
{
    public function generate()
    {
        return Excel::download(new ReportExport($this->title, $this->data), 'report.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
