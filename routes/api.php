<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintJobController;
use App\Http\Middleware\DesktopUniqueKeyMiddleware;

// called by Electron every X seconds
Route::middleware(DesktopUniqueKeyMiddleware::class)->group(function () {
    Route::get('/test-connection', [PrintJobController::class, 'testConnection']);
    Route::get('/print-jobs/pull', [PrintJobController::class, 'pull']);
    Route::get('/printer-details', [PrintJobController::class, 'printerDetails']);
    // mark a job done/failed
    Route::patch('/print-jobs/{printJob}', [PrintJobController::class, 'update']);
});

// Print Job API Routes for Desktop Applications
Route::prefix('print-jobs')->group(function () {
    // Get pending print jobs for a specific printer
    Route::get('/pending/{printerId}', function ($printerId) {
        $printJobs = \App\Models\PrintJob::where('printer_id', $printerId)
            ->where('status', 'pending')
            ->with('printer')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'print_jobs' => $printJobs
        ]);
    });

    // Get print jobs for a specific printer (using controller method)
    Route::get('/printer/{printerId}/jobs', [\App\Http\Controllers\PrintJobController::class, 'getPrinterJobs']);

    // Mark print job as completed
    Route::post('/{printJobId}/complete', function ($printJobId) {
        $printJob = \App\Models\PrintJob::findOrFail($printJobId);
        $printJob->update([
            'status' => 'completed',
            'printed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Print job marked as completed'
        ]);
    });

    // Mark print job as failed
    Route::post('/{printJobId}/failed', function ($printJobId) {
        $printJob = \App\Models\PrintJob::findOrFail($printJobId);
        $printJob->update([
            'status' => 'failed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Print job marked as failed'
        ]);
    });
});
