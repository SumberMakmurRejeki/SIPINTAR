<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\TrainingController;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Auth redirect route
Route::redirect('/', '/login');

// Guest-only route (login page handled by Fortify)

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', function () {
            $stats = [
                ['label' => 'Total Karyawan', 'value' => User::where('role', 'employee')->count(), 'route' => route('admin.master.karyawan.index')],
                ['label' => 'Departemen', 'value' => Department::where('status', 'active')->count(), 'route' => route('admin.master.departemen.index')],
                ['label' => 'Jabatan', 'value' => Position::where('status', 'active')->count(), 'route' => route('admin.master.jabatan.index')],
                ['label' => 'Admin', 'value' => User::where('role', 'admin')->where('status', 'active')->count(), 'route' => route('admin.master.admin-user.index')],
            ];

            return view('pages::admin.dashboard', compact('stats'));
        })->name('dashboard');

        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('karyawan', EmployeeController::class);
            Route::patch('karyawan/{karyawan}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('karyawan.toggle-status');

            Route::resource('departemen', DepartmentController::class)->parameters(['departemen' => 'departemen']);
            Route::patch('departemen/{departemen}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departemen.toggle-status');

            Route::resource('jabatan', PositionController::class)->parameters(['jabatan' => 'jabatan']);
            Route::patch('jabatan/{jabatan}/toggle-status', [PositionController::class, 'toggleStatus'])->name('jabatan.toggle-status');

            Route::resource('admin-user', AdminUserController::class);
            Route::patch('admin-user/{adminUser}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin-user.toggle-status');
        });

        Route::resource('training', TrainingController::class);
        Route::post('training/{training}/publish', [TrainingController::class, 'publish'])->name('training.publish');
        Route::post('training/{training}/close', [TrainingController::class, 'close'])->name('training.close');
        Route::post('training/{training}/archive', [TrainingController::class, 'archive'])->name('training.archive');
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
