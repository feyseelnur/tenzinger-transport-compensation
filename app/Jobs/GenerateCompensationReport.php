<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;
use App\Services\CompensationCalculator;
use League\Csv\ByteSequence;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateCompensationReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected string $year;
    /**
     * @var string
     */
    protected string $outputFilename;
    /**
     * Create a new job instance.
     */
    public function __construct($year, string $overrideFilename = null)
    {
        $this->year = $year;
        $this->outputFilename = $overrideFilename ?? $this->generateFilename();

    }

    /**
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function handle(CompensationCalculator $calculator): void
    {
        try {
            Log::info('Generating compensation report for year ' . $this->year);

            $employees = Employee::has('commute')->with('commute.transport')->get();

            $csv = Writer::createFromFileObject(new SplTempFileObject());

            $csv->setOutputBOM(ByteSequence::BOM_UTF8); // Optional: Add BOM for UTF-8$employees = Employee::has('commute')->with('commute.transport')->get();$employees = Employee::has('commute')->with('commute.transport')->get();
            $csv->insertOne(['Employee', 'Transport', 'Traveled Distance', 'Monthly Compensation', 'Payment Date']);
            $paymentDates = $calculator->getPaymentDatesForYear($this->year);
            foreach ($paymentDates as $paymentDate) {
                foreach ($employees as $employee) {

                $compensation = $calculator->calculateMonthlyCompensation($employee->commute);

                    $csv->insertOne([
                        $employee->name,
                        $employee->commute->transport->type,
                        $employee->commute->distance,
                        $compensation,
                        $paymentDate->format('Y-m-d')
                    ]);
                }
            }

            $csvContent = (string) $csv;
            Storage::put('csv/' . $this->outputFilename, $csvContent);

            Log::info('Generating compensation report completed.');
        } catch (Exception $e) {
            Log::error('Error generating compensation report: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @return string
     */
    public function getOutputFilename(): string
    {
        return $this->outputFilename;
    }

    /**
     * @return string
     */
    protected function generateFilename(): string
    {
        return 'compensation' . '-' .  date('Y-m-d') . '-' . md5(now()) . '.csv';
    }
}
