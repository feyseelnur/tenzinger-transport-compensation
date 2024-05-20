<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Employee;

class GenerateCompensationReportControllerTest extends TestCase
{

    public function testGenerateReport()
    {
        Employee::factory()->count(3)->create();

        $response = $this->postJson('/api/generate-report', ['year' => '2024']);
        $response->assertStatus(200);
        $csvResponse = $this->json('get', $response->json('url'), []);
        $csvResponse->assertStatus(200);
    }

    public function testGenerateReportDebug()
    {
        Employee::factory()->count(3)->create();

        $response = $this->post('/api/generate-report', [
            'year' => '2024',
            'debug' => true
        ]);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'in_progress', 'url' => route('retrieve', ['filename' => 'compensation_debug.csv']) ]);
        $csvResponse = $this->json('get', $response->json('url'), []);
        $csvResponse->assertStatus(200);
    }

    public function testGenerateReportNoSync()
    {
        Employee::factory()->count(3)->create();

        $response = $this->post('/api/generate-report', ['year' => '2024', 'wait_for_result' => false]);
        $response->assertStatus(200);

        $csvResponse = $this->json('get', $response->json('url'), []);
        $csvResponse->assertStatus(200);
    }
    public function testGenerateReportSync()
    {
        Employee::factory()->count(3)->create();

        $response = $this->post('/api/generate-report', ['year' => '2024', 'wait_for_result' => true]);
        $response->assertStatus(200);

        $csvResponse = $this->json('get', $response->json('url'), []);
        $csvResponse->assertStatus(200);
    }

}
