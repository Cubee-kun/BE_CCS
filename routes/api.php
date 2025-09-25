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

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Evaluasi publik (read-only)
Route::get('/evaluasi', [EvaluasiController::class, 'index']);
Route::get('/evaluasi/{id}', [EvaluasiController::class, 'show']);

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

    // ---------------- Perencanaan (CRUD) ----------------
    Route::get('/perencanaan', [PerencanaanController::class, 'index']);
    Route::post('/perencanaan', [PerencanaanController::class, 'store']);
    Route::get('/perencanaan/{id}', [PerencanaanController::class, 'show']);
    Route::put('/perencanaan/{id}', [PerencanaanController::class, 'update']);
    Route::delete('/perencanaan/{id}', [PerencanaanController::class, 'destroy']);

    // ---------------- Implementasi (CRUD) ----------------
    Route::get('/implementasi', [ImplementasiController::class, 'index']);
    Route::post('/implementasi', [ImplementasiController::class, 'store']);
    Route::get('/implementasi/{id}', [ImplementasiController::class, 'show']);
    Route::put('/implementasi/{id}', [ImplementasiController::class, 'update']);
    Route::delete('/implementasi/{id}', [ImplementasiController::class, 'destroy']);

    // ---------------- Monitoring (CRUD) ----------------
    Route::get('/monitoring', [MonitoringController::class, 'index']);
    Route::post('/monitoring', [MonitoringController::class, 'store']);
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show']);
    Route::put('/monitoring/{id}', [MonitoringController::class, 'update']);
    Route::delete('/monitoring/{id}', [MonitoringController::class, 'destroy']);

    // ---------------- Dokumentasi (CRUD) ----------------
    Route::get('/dokumentasi', [DokumentasiController::class, 'index']);
    Route::post('/dokumentasi', [DokumentasiController::class, 'store']);
    Route::get('/dokumentasi/{id}', [DokumentasiController::class, 'show']);
    Route::put('/dokumentasi/{id}', [DokumentasiController::class, 'update']);
    Route::delete('/dokumentasi/{id}', [DokumentasiController::class, 'destroy']);

    // ---------------- Users (CRUD) ----------------
    Route::get('/users', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:create,App\Models\User');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('can:view,user');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('can:update,user');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('can:delete,user');

    // ---------------- Laporan (CRUD) ----------------
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::get('/laporan/{id}', [LaporanController::class, 'show']);
    Route::put('/laporan/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);
    // Tambahan khusus
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak']);
    Route::get('/laporan/cetak/{id}', [LaporanController::class, 'cetakById']);

    // ---------------- Evaluasi (CRUD private) ----------------
    Route::post('/evaluasi', [EvaluasiController::class, 'store']);
    Route::put('/evaluasi/{id}', [EvaluasiController::class, 'update']);
    Route::delete('/evaluasi/{id}', [EvaluasiController::class, 'destroy']);
});

// Fallback JSON 404
Route::fallback(function () {
    return response()->json(['message' => 'Endpoint not found'], 404);
});
