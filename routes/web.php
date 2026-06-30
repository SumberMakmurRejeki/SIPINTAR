<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\PostTestController;
use App\Http\Controllers\Admin\PostTestQuestionController;
use App\Http\Controllers\Admin\PreTestController;
use App\Http\Controllers\Admin\PreTestQuestionController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\TrainingMaterialController;
use App\Http\Controllers\Admin\TrainingParticipantController;
use App\Http\Controllers\Employee\TrainingController as EmployeeTrainingController;
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

        // Training materials (nested under training, within admin middleware)
        Route::post('training/{training}/materials', [TrainingMaterialController::class, 'store'])->name('training.materials.store');
        Route::put('training/{training}/materials/{material}', [TrainingMaterialController::class, 'update'])->name('training.materials.update');
        Route::patch('training/{training}/materials/{material}/toggle-status', [TrainingMaterialController::class, 'toggleStatus'])->name('training.materials.toggle-status');
        Route::delete('training/{training}/materials/{material}', [TrainingMaterialController::class, 'destroy'])->name('training.materials.destroy');
        Route::get('training/{training}/materials/{material}/preview', [TrainingMaterialController::class, 'preview'])->name('training.materials.preview');
        Route::get('training/{training}/materials/{material}/download', [TrainingMaterialController::class, 'download'])->name('training.materials.download');

        Route::post('training/{training}/pre-test', [PreTestController::class, 'store'])->name('training.pre-test.store');
        Route::put('training/{training}/pre-test/{test}', [PreTestController::class, 'update'])->name('training.pre-test.update');
        Route::post('training/{training}/pre-test/{test}/questions', [PreTestQuestionController::class, 'store'])->name('training.pre-test.questions.store');
        Route::put('training/{training}/pre-test/{test}/questions/{question}', [PreTestQuestionController::class, 'update'])->name('training.pre-test.questions.update');
        Route::delete('training/{training}/pre-test/{test}/questions/{question}', [PreTestQuestionController::class, 'destroy'])->name('training.pre-test.questions.destroy');
        Route::patch('training/{training}/pre-test/{test}/questions/{question}/toggle-status', [PreTestQuestionController::class, 'toggleStatus'])->name('training.pre-test.questions.toggle-status');
        Route::get('training/{training}/pre-test/{test}/preview', [PreTestQuestionController::class, 'preview'])->name('training.pre-test.preview');

        // Post-Test routes
        Route::post('training/{training}/post-test', [PostTestController::class, 'store'])->name('training.post-test.store');
        Route::put('training/{training}/post-test/{test}', [PostTestController::class, 'update'])->name('training.post-test.update');
        Route::post('training/{training}/post-test/{test}/copy-from-pretest', [PostTestController::class, 'copyFromPretest'])->name('training.post-test.copy-from-pretest');
        Route::post('training/{training}/post-test/{test}/questions', [PostTestQuestionController::class, 'store'])->name('training.post-test.questions.store');
        Route::put('training/{training}/post-test/{test}/questions/{question}', [PostTestQuestionController::class, 'update'])->name('training.post-test.questions.update');
        Route::delete('training/{training}/post-test/{test}/questions/{question}', [PostTestQuestionController::class, 'destroy'])->name('training.post-test.questions.destroy');
        Route::patch('training/{training}/post-test/{test}/questions/{question}/toggle-status', [PostTestQuestionController::class, 'toggleStatus'])->name('training.post-test.questions.toggle-status');
        Route::get('training/{training}/post-test/{test}/preview', [PostTestQuestionController::class, 'preview'])->name('training.post-test.preview');

        // Training participants (nested under training)
        Route::post('training/{training}/participants', [TrainingParticipantController::class, 'store'])->name('training.participants.store');
        Route::get('training/{training}/participants/{participant}', [TrainingParticipantController::class, 'show'])->name('training.participants.show');
        Route::delete('training/{training}/participants/{participant}', [TrainingParticipantController::class, 'destroy'])->name('training.participants.destroy');

        Route::view('penilaian', 'pages::admin.penilaian')->name('penilaian');
        Route::view('monitoring-progress', 'pages::admin.monitoring-progress')->name('monitoring-progress');
        Route::view('laporan-training', 'pages::admin.laporan-training')->name('laporan-training');
        Route::view('profil-password', 'pages::admin.profil-password')->name('profil-password');
    });

    // Employee routes
    Route::middleware(['role:employee'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::view('dashboard', 'pages::employee.dashboard')->name('dashboard');
        Route::view('riwayat-training', 'pages::employee.riwayat-training')->name('riwayat-training');
        Route::view('profil-password', 'pages::employee.profil-password')->name('profil-password');

        // Training Saya
        Route::get('training-saya', [EmployeeTrainingController::class, 'index'])->name('training-saya.index');
        Route::get('training-saya/{training}', [EmployeeTrainingController::class, 'show'])->name('training-saya.show');
        Route::post('training-saya/{training}/materials/{material}/access', [EmployeeTrainingController::class, 'accessMaterial'])->name('training-saya.materials.access');
        Route::get('training-saya/{training}/materials/{material}/download', [EmployeeTrainingController::class, 'downloadMaterial'])->name('training-saya.materials.download');
    });
});

require __DIR__.'/settings.php';
