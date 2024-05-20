<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Jobs\GenerateCompensationReport;
use App\Models\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Models\Commute;
use App\Models\Transport;
use App\Services\CompensationCalculator;

class GenerateCompensationReportTest extends TestCase
{
    use RefreshDatabase;


    public function testJobIsDispatched()
    {
        Queue::fake();
        $response = $this->post('/api/generate-report', [
            'year' => date('Y'),
        ]);
        $response->assertStatus(200);

        Queue::assertPushed(GenerateCompensationReport::class);
    }

    public function testJobGeneratesReport()
    {
        Storage::fake();
        $transportBike = Transport::create(['type' => Transport::TYPE_BIKE, 'rate' => 0.50]);
        $transportCar = Transport::create(['type' => Transport::TYPE_CAR, 'rate' => 0.10]);
        $employee1 = Employee::factory()->create();
        Commute::factory()->create([
            'employee_id' => $employee1->id,
            'transport_id' => $transportBike->id,
            'distance' => 6,
            'workdays_per_week' => 5,
        ]);

        $employee2 = Employee::factory()->create();
        Commute::factory()->create([
            'employee_id' => $employee2->id,
            'transport_id' => $transportCar->id,
            'distance' => 12,
            'workdays_per_week' => 5,
        ]);
        $calculator = new CompensationCalculator();
        $job = new GenerateCompensationReport(date('Y'), null);
        $job->handle($calculator);

        $outputFilename = $job->getOutputFilename();
        Storage::assertExists('csv/' . $outputFilename);
        $content = Storage::get('csv/' . $outputFilename);

        $this->assertStringContainsString('Employee', $content);
        $this->assertStringContainsString('Transport', $content);
        $this->assertStringContainsString('Traveled Distance', $content);
        $this->assertStringContainsString('Monthly Compensation', $content);
        $this->assertStringContainsString('Payment Date', $content);

    }
}
