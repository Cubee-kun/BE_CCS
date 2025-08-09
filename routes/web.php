<?php

use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return view('dashboard.index', [
        'stats' => [
            'total_perencanaan' => 0,
            'total_implementasi' => 0,
            'total_monitoring' => 0,
        ],
        'recentActivities' => [],
    ]);
})->name('dashboard');

// Auth
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');

// Perencanaan
Route::get('/perencanaan', function () { return view('dashboard.perencanaan'); })->name('perencanaan.create');

// Implementasi
Route::get('/implementasi', function () { return view('dashboard.implementasi', ['perencanaan' => (object)['nama_perusahaan'=>'','jenis_kegiatan'=>'','lokasi'=>'','id'=>1]]); })->name('implementasi.create');

// Monitoring
Route::get('/monitoring', function () { return view('dashboard.monitoring'); })->name('monitoring.create');