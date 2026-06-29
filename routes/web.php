<?php

use Illuminate\Support\Facades\Route;

// Auth redirect route
Route::redirect('/', '/login');

// Guest-only route (login page handled by Fortify)

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::view('dashboard', 'pages::admin.dashboard')->name('dashboard');

        Route::prefix('master')->name('master.')->group(function () {
            Route::view('karyawan', 'pages::admin.master.karyawan')->name('karyawan');
            Route::view('departemen', 'pages::admin.master.departemen')->name('departemen');
            Route::view('jabatan', 'pages::admin.master.jabatan')->name('jabatan');
            Route::view('admin-user', 'pages::admin.master.admin-user')->name('admin-user');
        });

        Route::view('training', 'pages::admin.training')->name('training');
        Route::view('penilaian', 'pages::admin.penilaian')->name('penilaian');
        Route::view('monitoring-progress', 'pages::admin.monitoring-progress')->name('monitoring-progress');
        Route::view('laporan-training', 'pages::admin.laporan-training')->name('laporan-training');
        Route::view('profil-password', 'pages::admin.profil-password')->name('profil-password');
    });

    // Employee routes
    Route::middleware(['role:employee'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::view('dashboard', 'pages::employee.dashboard')->name('dashboard');
        Route::view('training-saya', 'pages::employee.training-saya')->name('training-saya');
        Route::view('riwayat-training', 'pages::employee.riwayat-training')->name('riwayat-training');
        Route::view('profil-password', 'pages::employee.profil-password')->name('profil-password');
    });
});

require __DIR__.'/settings.php';
