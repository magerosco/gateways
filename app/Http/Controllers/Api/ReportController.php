<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Factories\ReportFactory;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportDirector;


class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|string|in:pdf,excel,json',
        ]);
        try {
            $data = [['name' => 'Product 1', 'price' => 100], ['name' => 'Product 2', 'price' => 200]];

            //EXAMPLE: Builder pattern to use the ReportDirector class to build the report
            $director = new ReportDirector();

            //EXAMPLE: Strategy pattern to choose the report format
            $report = ReportFactory::make($validated['format']);

            return $director->buildReport($report, 'Report', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
