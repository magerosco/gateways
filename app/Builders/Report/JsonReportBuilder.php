<?php

namespace App\Builders\Report;

class JsonReportBuilder extends ReportBuilder
{
    public function generate()
    {
        return response()->json([
            'title' => $this->title,
            'data' => $this->data
        ]);
    }
}
