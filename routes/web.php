<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("/login");
});

Route::get('/get-desa/{kecamatan}', [LaporanController::class, 'getDesa']);

Route::middleware(['auth:sanctum', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::middleware(['admin'])->group(function() {
        Route::get('/users', [UsersController::class, 'index'])->name('dashboard.users');
        Route::get('/users/create', [UsersController::class, 'create'])->name('dashboard.users.create');
        Route::post('/users', [UsersController::class, 'store'])->name('dashboard.users.store');
        Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('dashboard.users.edit');
        Route::put('/users/{user}', [UsersController::class, 'update'])->name('dashboard.users.update');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('dashboard.users.destroy');
        Route::get('/data-laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/data-laporan/filter-by-date', [LaporanController::class, 'getDatabyDate'])->name('admin.laporan.getDataPerDate');
        Route::patch('data-laporan/{report}/validate', [LaporanController::class, 'validateReport'])->name('dashboard.reports.validate');
        Route::patch('data-laporan/{report}/reject', [LaporanController::class, 'rejectReport'])->name('dashboard.reports.reject');
    });

    Route::middleware(['petugas'])->group(function() {
        Route::get('/laporan/tambah', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{id}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    });
});
