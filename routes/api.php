<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\EvaluasiController;

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

    // Form routes
    Route::prefix('forms')->group(function () {
        Route::post('/perencanaan', [FormController::class, 'createPerencanaan']);
        Route::get('/perencanaan/{id}', [FormController::class, 'getPerencanaan']);
        Route::post('/implementasi/{perencanaan_id}', [FormController::class, 'createImplementasi']);
        Route::post('/monitoring/{implementasi_id}', [FormController::class, 'createMonitoring']);
        Route::get('/user', [FormController::class, 'getUserForms']);
        Route::post('/upload', [FormController::class, 'uploadDokumentasi']);
    });

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
