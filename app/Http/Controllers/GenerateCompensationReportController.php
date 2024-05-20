<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateReportRequest;
use App\Jobs\GenerateCompensationReport;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Storage;

class GenerateCompensationReportController extends Controller
{
    public const PDF_DIRECTORY = 'csv/';
    public const MAX_ATTEMPTS = 20;

    public function __invoke(GenerateReportRequest $request, ResponseFactory $response)
    {
        $status = 'in_progress';
        $year = $request->validated()['year'];
        $overrideFilename = null;

        if ($request->get('debug')) {
            $overrideFilename = 'compensation_debug.csv';
        }

        $job = new GenerateCompensationReport($year, $overrideFilename);
        $filename = $job->getOutputFilename();

        if ($request->get('wait_for_result')) {
            if (windows_os()) {
                dispatch($job);

                if ($this->pollIfCsvIsCreated($filename)) {
                    $status = 'created';
                } else {
                    $status = 'unknown';
                }
            } else {
                dispatch_sync($job);
                $status = 'created';
            }
        } else {
            dispatch($job);
        }

        return $response->json([
            'status' => $status,
            'url' => route('retrieve', ['filename' => $filename])
        ]);
    }

    private function pollIfCsvIsCreated($filename): bool
    {
        $attempts = 0;
        $path = self::PDF_DIRECTORY . $filename;

        do {
            if (Storage::exists($path)) {
                return true;
            }

            sleep(1);

            $attempts++;
        } while ($attempts < self::MAX_ATTEMPTS);

        return false;}
}
