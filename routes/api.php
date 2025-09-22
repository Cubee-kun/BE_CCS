<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\PerencanaanController;
use App\Http\Controllers\ImplementasiController;
use App\Http\Controllers\MonitoringController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================
// Public routes
// ==========================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Evaluasi publik (hanya read)
Route::apiResource('evaluasi', EvaluasiController::class)->only(['index', 'show']);


// ==========================
// Authenticated routes
// ==========================
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    // Dashboard routes
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Perencanaan
    Route::post('/perencanaan', [PerencanaanController::class, 'store']);
    Route::get('/perencanaan/{id}', [PerencanaanController::class, 'show']);

    // Implementasi
    Route::post('/implementasi/{perencanaan_id}', [ImplementasiController::class, 'store']);

    // Monitoring
    Route::post('/monitoring/{implementasi_id}', [MonitoringController::class, 'store']);

    // Dokumentasi
    Route::post('/upload', [DokumentasiController::class, 'upload']);

    // User management routes (admin only, gunakan policy / gate)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
        Route::post('/', [UserController::class, 'store'])->middleware('can:create,App\Models\User');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('can:view,user');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('can:update,user');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user');
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/{id}', [LaporanController::class, 'show']);
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak']);

    // Evaluasi full CRUD (admin / user login)
    Route::apiResource('evaluasi', EvaluasiController::class)->except(['index', 'show']);
});