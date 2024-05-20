<?php

use App\Http\Controllers\GenerateCompensationReportController;
use App\Http\Controllers\RetrieveCompensationReportController;
use Illuminate\Support\Facades\Route;



Route::post('/generate-report', GenerateCompensationReportController::class)->name('generate-report');
Route::get('/retrieve/{filename}', RetrieveCompensationReportController::class)->name('retrieve');
