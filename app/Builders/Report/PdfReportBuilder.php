<?php

namespace App\Builders\Report;

use Barryvdh\DomPDF\Facade\Pdf;




class PdfReportBuilder extends ReportBuilder
{
    public function generate()
    {
        $pdf = Pdf::loadView('reports.pdf', ['title' => $this->title, 'data' => $this->data]);
        return $pdf->download('report.pdf');
    }
}
