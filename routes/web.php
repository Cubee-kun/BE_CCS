<?php

use Illuminate\Support\Facades\Route;
// API-only backend: no Blade views, return simple JSON for web routes

// Root endpoint - basic API info
Route::get('/', function () {
    return response()->json([
        'app' => config('app.name', 'CCS API'),
        'status' => 'ok',
        'version' => app()->version(),
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('root');

// Optional: simple web fallback to JSON 404 (separate from API fallback)
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});
