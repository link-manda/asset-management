<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetAssignmentController;
use App\Http\Controllers\AssetMaintenanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UnitOfMeasurementController;
use App\Http\Controllers\AssetDisposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DepreciationReportController;

use App\Http\Controllers\Auth\LoginController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Inventory Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Asset (Katalog)
    Route::resource('assets', AssetController::class)->except(['destroy']);
    Route::resource('disposals', AssetDisposalController::class)->only(['index', 'store']);
    Route::delete('assets/images/{image}', [\App\Http\Controllers\AssetImageController::class, 'destroy'])->name('assets.images.destroy');

    // Inventory List (Physical Items Global)
    Route::get('/inventory', [\App\Http\Controllers\AssetItemController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [\App\Http\Controllers\AssetItemController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{item}', [\App\Http\Controllers\AssetItemController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/{item}/edit', [\App\Http\Controllers\AssetItemController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{item}', [\App\Http\Controllers\AssetItemController::class, 'update'])->name('inventory.update');
    Route::post('/inventory/bulk-print', [\App\Http\Controllers\AssetItemController::class, 'bulkPrint'])->name('inventory.bulk-print');

    // Asset Maintenance
    Route::resource('maintenances', AssetMaintenanceController::class);

    // Asset Assignments
    Route::get('/assignments', [AssetAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/items/{item}/checkout', [AssetAssignmentController::class, 'create'])->name('items.checkout.create');
    Route::post('/items/{item}/checkout', [AssetAssignmentController::class, 'checkoutStore'])->name('items.checkout');
    Route::post('/items/{item}/checkin', [AssetAssignmentController::class, 'checkinStore'])->name('items.checkin');

    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('uoms', UnitOfMeasurementController::class);

    // User Management
    Route::resource('users', UserController::class);
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::resource('roles', RoleController::class);
    });
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/settings', fn() => redirect()->route('dashboard'))->name('settings.index');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/general', [ReportController::class, 'generalReport'])->name('general');
        Route::get('/general/export/excel', [ReportController::class, 'exportExcel'])->name('general.excel');
        Route::get('/general/export/csv', [ReportController::class, 'exportCsv'])->name('general.csv');
        Route::get('/general/export/pdf', [ReportController::class, 'exportPdf'])->name('general.pdf');
        Route::get('/depreciation', [DepreciationReportController::class, 'index'])->name('depreciation');
        Route::get('/depreciation/export', [DepreciationReportController::class, 'export'])->name('depreciation.export');

        Route::get('/summary', [\App\Http\Controllers\AssetSummaryController::class, 'index'])->name('summary');
        Route::get('/summary/export/excel', [\App\Http\Controllers\AssetSummaryController::class, 'exportExcel'])->name('summary.excel');
        Route::get('/summary/export/csv', [\App\Http\Controllers\AssetSummaryController::class, 'exportCsv'])->name('summary.csv');
        Route::get('/summary/export/pdf', [\App\Http\Controllers\AssetSummaryController::class, 'exportPdf'])->name('summary.pdf');
    });
});

// Fallback untuk route template Tailwick agar tidak error
Route::get('/demo/{any?}/{any2?}', function () {
    return redirect()->route('assets.index');
})->name('second');
