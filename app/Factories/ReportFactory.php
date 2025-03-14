<?php

namespace App\Factories;

use App\Builders\Report\ReportBuilderInterface;
use App\Builders\Report\{PdfReportBuilder, ExcelReportBuilder, JsonReportBuilder};

class ReportFactory
{
    public static function make(string $type): ReportBuilderInterface | \Exception
    {
        return match ($type) {
            'pdf' => new PdfReportBuilder(),
            'json' => new JsonReportBuilder(),
            'excel' => new ExcelReportBuilder(),
            default => throw new \Exception('Invalid report type'),
        };
    }
}
