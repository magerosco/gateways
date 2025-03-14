<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Services\Report\ReportDirector;
use Illuminate\Support\Facades\Artisan;
use App\Builders\Report\PdfReportBuilder;
use App\Builders\Report\JsonReportBuilder;
use App\Builders\Report\ExcelReportBuilder;

class ReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh --seed --env="testing"');
    }

    public function test_excel_report_builder_instantiation()
    {
        $builder = new ExcelReportBuilder();
        $this->assertInstanceOf(ExcelReportBuilder::class, $builder);
    }

    public function test_generate_pdf_report()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];
        $director = new ReportDirector();
        $builder = new PdfReportBuilder();

        $response = $director->buildReport($builder, 'Report', $data);

        // \Barryvdh\DomPDF\Facade\PDF->download() return \Illuminate\Http\Response
        $this->assertInstanceOf(\Illuminate\Http\Response::class, $response);
    }

    public function test_generate_json_report()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];
        $director = new ReportDirector();
        $builder = new JsonReportBuilder();

        $response = $director->buildReport($builder, 'Report', $data);

        $this->assertJson($response->getContent());
    }

    public function test_generate_excel_report()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];
        $director = new ReportDirector();
        $builder = new ExcelReportBuilder();

        $response = $director->buildReport($builder, 'Report', $data);

        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    }

    public function test_data_passed_to_report_is_correct()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];
        $director = new ReportDirector();
        $builder = new JsonReportBuilder();

        $response = $director->buildReport($builder, 'Report', $data);
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Product 1', $responseData['data'][0]['name']);
        $this->assertEquals(100, $responseData['data'][0]['price']);
    }

    public function test_generate_report_with_different_formats()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];

        // PDF
        $response = $this->postJson('/api/report/generate', ['format' => 'pdf', 'data' => $data]);
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));

        // Excel
        $response = $this->postJson('/api/report/generate', ['format' => 'excel', 'data' => $data]);
        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));

        // JSON
        $response = $this->postJson('/api/report/generate', ['format' => 'json', 'data' => $data]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['title', 'data']);
        $this->assertJson($response->getContent());
    }

    public function test_generate_report_with_missing_format()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];

        $response = $this->postJson('/api/report/generate', ['data' => $data]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('format');
    }

    public function test_generate_report_with_invalid_format()
    {
        $data = [['name' => 'Product 1', 'price' => 100]];

        $response = $this->postJson('/api/report/generate', ['format' => 'invalid', 'data' => $data]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('format');
    }

    public function test_generate_report_performance()
    {
        $start = microtime(true);

        $data = array_fill(0, 5000, ['name' => 'Product', 'price' => 100]);

        $response = $this->postJson('/api/report/generate', ['format' => 'json', 'data' => $data]);
        $response->assertStatus(200);

        $time = microtime(true) - $start;

        $this->assertLessThan(1, $time);
    }
}
