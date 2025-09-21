<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (requires authentication)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified']);

    // All authenticated routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Admin routes for administrative divisions
        Route::prefix('admin')->name('admin.')->middleware(['role:super-admin,district-admin'])->group(function () {
        // Zilla management
        Route::resource('zillas', App\Http\Controllers\Admin\ZillaController::class);
        
        // Upazila management
        Route::resource('upazilas', App\Http\Controllers\Admin\UpazilaController::class);
        Route::get('upazilas-by-zilla/{zilla}', [App\Http\Controllers\Admin\UpazilaController::class, 'getByZilla'])->name('upazilas.by-zilla');
        
        // Union management
        Route::resource('unions', App\Http\Controllers\Admin\UnionController::class);
        Route::get('unions-by-upazila/{upazila}', [App\Http\Controllers\Admin\UnionController::class, 'getByUpazila'])->name('unions.by-upazila');
        
        // Ward management
        Route::resource('wards', App\Http\Controllers\Admin\WardController::class);
        Route::get('wards-by-union/{union}', [App\Http\Controllers\Admin\WardController::class, 'getByUnion'])->name('wards.by-union');
        
        // Economic Year management
        Route::resource('economic-years', App\Http\Controllers\Admin\EconomicYearController::class);
        
        // Relief Type management
        Route::resource('relief-types', App\Http\Controllers\Admin\ReliefTypeController::class);
        
        // Project management
        Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class);
        
        // Organization Type management
        Route::resource('organization-types', App\Http\Controllers\Admin\OrganizationTypeController::class);
        
        // Relief Item management
        Route::resource('relief-items', App\Http\Controllers\Admin\ReliefItemController::class);
        Route::post('relief-items/{id}/restore', [App\Http\Controllers\Admin\ReliefItemController::class, 'restore'])->name('relief-items.restore');
        Route::get('relief-items-by-type', [App\Http\Controllers\Admin\ReliefItemController::class, 'getByType'])->name('relief-items.by-type');
        
        // Relief Application management
        Route::resource('relief-applications', App\Http\Controllers\Admin\ReliefApplicationController::class)->middleware(['permission:relief-applications.approve,relief-applications.reject']);
        Route::get('projects-by-relief-type', [App\Http\Controllers\Admin\ReliefApplicationController::class, 'getProjectsByReliefType'])->name('projects.by-relief-type');
        Route::get('project-budget', [App\Http\Controllers\Admin\ReliefApplicationController::class, 'getProjectBudget'])->name('project.budget');
        
        // Audit Log management
        Route::resource('audit-logs', App\Http\Controllers\Admin\AuditLogController::class)->only(['index', 'show', 'destroy']);
        Route::post('audit-logs/clear', [App\Http\Controllers\Admin\AuditLogController::class, 'clear'])->name('audit-logs.clear');
        
        // Export functionality
        Route::prefix('exports')->name('exports.')->middleware(['permission:exports.access'])->group(function () {
            // Relief Applications Export
            Route::get('relief-applications/excel', [App\Http\Controllers\ExportController::class, 'exportReliefApplicationsExcel'])->name('relief-applications.excel');
            Route::get('relief-applications/pdf', [App\Http\Controllers\ExportController::class, 'exportReliefApplicationsPdf'])->name('relief-applications.pdf');
            
            // Project Summary Export
            Route::get('project-summary/excel', [App\Http\Controllers\ExportController::class, 'exportProjectSummaryExcel'])->name('project-summary.excel');
            Route::get('project-summary/pdf', [App\Http\Controllers\ExportController::class, 'exportProjectSummaryPdf'])->name('project-summary.pdf');
            
            // Area-wise Relief Export
            Route::get('area-wise-relief/excel', [App\Http\Controllers\ExportController::class, 'exportAreaWiseReliefExcel'])->name('area-wise-relief.excel');
            Route::get('area-wise-relief/pdf', [App\Http\Controllers\ExportController::class, 'exportAreaWiseReliefPdf'])->name('area-wise-relief.pdf');
        });
    });

    // Relief Application routes (accessible to authenticated users)
    Route::resource('relief-applications', App\Http\Controllers\ReliefApplicationController::class)->middleware(['permission:relief-applications.create-own,relief-applications.view-own']);
    Route::get('upazilas-by-zilla/{zilla}', [App\Http\Controllers\ReliefApplicationController::class, 'getUpazilasByZilla'])->name('upazilas.by-zilla');
    Route::get('unions-by-upazila/{upazila}', [App\Http\Controllers\ReliefApplicationController::class, 'getUnionsByUpazila'])->name('unions.by-upazila');
    Route::get('wards-by-union/{union}', [App\Http\Controllers\ReliefApplicationController::class, 'getWardsByUnion'])->name('wards.by-union');
});

require __DIR__.'/auth.php';
