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
| Prefix "api" sudah diset di App\Providers\RouteServiceProvider
| Group "api" pipeline: HandleCors, Throttle:api, SubstituteBindings (lihat bootstrap/app.php)
*/

// Public routes (tanpa auth)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Evaluasi publik (read-only)
Route::apiResource('evaluasi', EvaluasiController::class)->only(['index', 'show']);

// Healthcheck sederhana
Route::get('/ping', fn () => response()->json(['pong' => true]));

// Authenticated routes (JWT)
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Perencanaan
    Route::post('/perencanaan', [PerencanaanController::class, 'store']);
    Route::get('/perencanaan/{id}', [PerencanaanController::class, 'show']);

    // Implementasi
    Route::post('/implementasi/{perencanaan_id}', [ImplementasiController::class, 'store']);

    // Monitoring
    Route::post('/monitoring/{implementasi_id}', [MonitoringController::class, 'store']);

    // Dokumentasi (upload)
    Route::post('/upload', [DokumentasiController::class, 'upload']);

    // User management (proteksi via policy)
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
    Route::get('/laporan/cetak/{id}', [LaporanController::class, 'cetakById']);

    // Evaluasi full CRUD (admin/user login)
    Route::apiResource('evaluasi', EvaluasiController::class)->except(['index', 'show']);
});

// Fallback JSON 404 untuk endpoint yang tidak ditemukan
Route::fallback(function () {
    return response()->json(['message' => 'Endpoint not found'], 404);
});