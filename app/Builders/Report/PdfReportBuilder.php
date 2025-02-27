<?php

namespace App\Builders\Report;

use Barryvdh\DomPDF\Facade\PDF;




class PdfReportBuilder extends ReportBuilder
{
    public function generate()
    {
        $pdf = PDF::loadView('reports.pdf', ['title' => $this->title, 'data' => $this->data]);
        return $pdf->download('report.pdf');
    }
}
