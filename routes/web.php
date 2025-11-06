<?php

use Illuminate\Support\Facades\Route;

// API-only backend - return JSON response for root
Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'CCS Backend API is running',
        'api_docs' => '/api/documentation'
    ]);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});
