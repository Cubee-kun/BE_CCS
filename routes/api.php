<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\DashboardController;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    // Dashboard routes
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Perencanaan routes
    Route::post('/perencanaan', [FormController::class, 'createPerencanaan']);
    Route::get('/perencanaan/{id}', [FormController::class, 'getPerencanaan']);

    // Implementasi routes
    Route::post('/implementasi/{perencanaan_id}', [FormController::class, 'createImplementasi']);

    // Monitoring routes
    Route::post('/monitoring/{implementasi_id}', [FormController::class, 'createMonitoring']);

    // User forms
    Route::get('/user/forms', [FormController::class, 'getUserForms']);

    Route::post('/upload', [FormController::class, 'uploadDokumentasi']);
});

// Swagger documentation route
Route::get('/api/documentation', function() {
    return view('swagger.index');
})->middleware('auth:api');
