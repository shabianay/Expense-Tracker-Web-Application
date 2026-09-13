<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('transactions', TransactionController::class);
Route::resource('categories', CategoryController::class);
Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
Route::get('/charts', [ChartController::class, 'index'])->name('charts.index');
Route::get('/export', [ExportController::class, 'export'])->name('export');
