<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\DashboardController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);

    // Form routes
    Route::post('/perencanaan', [FormController::class, 'createPerencanaan']);
    Route::post('/implementasi', [FormController::class, 'createImplementasi']);

    // Monitoring routes
    Route::post('/monitoring', [MonitoringController::class, 'createMonitoring']);
    Route::get('/monitoring/{implementasi_id}', [MonitoringController::class, 'getMonitoringData']);
});

// Swagger documentation route (protected)
Route::group([
    'middleware' => ['jwt.auth', 'swagger'],
    'prefix' => 'docs'
], function () {
    Route::get('/', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('l5swagger.api');
    Route::get('/asset/{asset}', '\L5Swagger\Http\Controllers\SwaggerController@asset')->name('l5swagger.asset');
    Route::get('/oauth2_callback', '\L5Swagger\Http\Controllers\SwaggerController@oauth2Callback')->name('l5swagger.oauth2_callback');
});
