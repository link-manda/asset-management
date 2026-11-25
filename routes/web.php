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
    Route::middleware(['permission:create assets'])->group(function () {
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    });

    Route::middleware(['permission:view assets'])->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    });

    Route::middleware(['permission:edit assets'])->group(function () {
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('assets/images/{image}', [\App\Http\Controllers\AssetImageController::class, 'destroy'])->name('assets.images.destroy');
    });

    Route::middleware(['permission:view assets'])->group(function () {
        Route::resource('disposals', AssetDisposalController::class)->only(['index']);
    });
    Route::middleware(['permission:edit assets'])->group(function () {
        Route::post('/disposals', [AssetDisposalController::class, 'store'])->name('disposals.store');
    });

    // Inventory List (Physical Items Global)
    Route::middleware(['permission:view assets'])->group(function () {
        Route::get('/inventory', [\App\Http\Controllers\AssetItemController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/{item}', [\App\Http\Controllers\AssetItemController::class, 'show'])->name('inventory.show');
    });

    Route::middleware(['permission:create assets'])->group(function () {
        Route::post('/inventory', [\App\Http\Controllers\AssetItemController::class, 'store'])->name('inventory.store');
    });

    Route::middleware(['permission:edit assets'])->group(function () {
        Route::get('/inventory/{item}/edit', [\App\Http\Controllers\AssetItemController::class, 'edit'])->name('inventory.edit');
        Route::put('/inventory/{item}', [\App\Http\Controllers\AssetItemController::class, 'update'])->name('inventory.update');
    });

    Route::post('/inventory/bulk-print', [\App\Http\Controllers\AssetItemController::class, 'bulkPrint'])->name('inventory.bulk-print');

    // Asset Maintenance
    Route::middleware(['permission:view assets'])->group(function () {
        Route::get('/maintenances', [AssetMaintenanceController::class, 'index'])->name('maintenances.index');
        Route::get('/maintenances/{maintenance}', [AssetMaintenanceController::class, 'show'])->name('maintenances.show');
    });
    Route::middleware(['permission:edit assets'])->group(function () {
        Route::resource('maintenances', AssetMaintenanceController::class)->except(['index', 'show']);
    });

    // Asset Assignments
    Route::middleware(['permission:view assets'])->group(function () {
        Route::get('/assignments', [AssetAssignmentController::class, 'index'])->name('assignments.index');
    });
    Route::middleware(['permission:edit assets'])->group(function () {
        Route::get('/items/{item}/checkout', [AssetAssignmentController::class, 'create'])->name('items.checkout.create');
        Route::post('/items/{item}/checkout', [AssetAssignmentController::class, 'checkoutStore'])->name('items.checkout');
        Route::post('/items/{item}/checkin', [AssetAssignmentController::class, 'checkinStore'])->name('items.checkin');
    });

    // Master Data
    Route::middleware(['permission:view categories'])->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    });
    Route::middleware(['permission:create categories'])->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    });
    Route::middleware(['permission:edit categories'])->group(function () {
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });
    Route::middleware(['permission:delete categories'])->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware(['permission:view locations'])->group(function () {
        Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
        Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
    });
    Route::middleware(['permission:create locations'])->group(function () {
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    });
    Route::middleware(['permission:edit locations'])->group(function () {
        Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    });
    Route::middleware(['permission:delete locations'])->group(function () {
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    });

    Route::middleware(['permission:view divisions'])->group(function () {
        Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    });
    Route::middleware(['permission:create divisions'])->group(function () {
        Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
        Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    });
    Route::middleware(['permission:edit divisions'])->group(function () {
        Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
        Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
    });
    Route::middleware(['permission:delete divisions'])->group(function () {
        Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');
    });

    Route::middleware(['permission:view departments'])->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    });
    Route::middleware(['permission:create departments'])->group(function () {
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    });
    Route::middleware(['permission:edit departments'])->group(function () {
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    });
    Route::middleware(['permission:delete departments'])->group(function () {
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    });

    Route::middleware(['permission:view uoms'])->group(function () {
        Route::get('/uoms', [UnitOfMeasurementController::class, 'index'])->name('uoms.index');
    });
    Route::middleware(['permission:create uoms'])->group(function () {
        Route::post('/uoms', [UnitOfMeasurementController::class, 'store'])->name('uoms.store');
    });
    Route::middleware(['permission:edit uoms'])->group(function () {
        Route::put('/uoms/{uom}', [UnitOfMeasurementController::class, 'update'])->name('uoms.update');
    });
    Route::middleware(['permission:delete uoms'])->group(function () {
        Route::delete('/uoms/{uom}', [UnitOfMeasurementController::class, 'destroy'])->name('uoms.destroy');
    });

    // User Management & Profile
    Route::get('/profile', fn() => redirect()->route('users.edit', auth()->id()))->name('profile');

    Route::middleware(['permission:view users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    Route::middleware(['permission:create users'])->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });
    
    // Edit/Update handled by controller logic for 'self' access
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::middleware(['permission:delete users'])->group(function () {
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
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
