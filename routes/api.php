<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\DashboardController;

Route::prefix('api')->group(function () {
    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::middleware('auth:api')->group(function () {
        // Dashboard routes
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        // Form routes
        Route::post('/perencanaan', [FormController::class, 'createPerencanaan']);
        Route::get('/perencanaan/{id}', [FormController::class, 'getPerencanaan']);
        Route::post('/implementasi/{perencanaan_id}', [FormController::class, 'createImplementasi']);
        Route::post('/monitoring/{implementasi_id}', [FormController::class, 'createMonitoring']);
        Route::get('/user/forms', [FormController::class, 'getUserForms']);
        Route::post('/upload', [FormController::class, 'uploadDokumentasi']);
    });
});
