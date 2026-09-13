<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// App Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::get('/charts', [ChartController::class, 'index'])->name('charts.index');
    Route::get('/export', [ExportController::class, 'export'])->name('export');
});
