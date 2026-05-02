<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetAssignmentController;
use App\Http\Controllers\AssetMaintenanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UnitOfMeasurementController;

use App\Http\Controllers\Auth\LoginController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Inventory Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Asset
    Route::match(['get', 'post'], '/assets-bulk-print', [AssetController::class, 'bulkPrint'])->name('assets.bulk-print');
    Route::get('/assets/{asset}/print', [AssetController::class, 'printLabel'])->name('assets.print');
    Route::resource('assets', AssetController::class);
    Route::delete('assets/images/{image}', [\App\Http\Controllers\AssetImageController::class, 'destroy'])->name('assets.images.destroy');

    // Asset Maintenance
    Route::resource('maintenances', AssetMaintenanceController::class);

    // Asset Assignments
    Route::get('/assignments', [AssetAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assets/{asset}/checkout', [AssetAssignmentController::class, 'create'])->name('assets.checkout.create');
    Route::post('/assets/{asset}/checkout', [AssetAssignmentController::class, 'checkoutStore'])->name('assets.checkout');
    Route::post('/assets/{asset}/checkin', [AssetAssignmentController::class, 'checkinStore'])->name('assets.checkin');

    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('uoms', UnitOfMeasurementController::class);

    // User Management
    Route::resource('users', UserController::class);
    Route::get('/settings', fn() => redirect()->route('dashboard'))->name('settings.index');
});

// Fallback untuk route template Tailwick agar tidak error
Route::get('/demo/{any?}/{any2?}', function () {
    return redirect()->route('assets.index');
})->name('second');
