<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\ChecklistController;
use App\Http\Controllers\Backoffice\DomainController;
use App\Http\Controllers\Backoffice\CompanyController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Backoffice\CompanyQcmController;
use App\Http\Controllers\Backoffice\UserActionPlanController; // New Import
use App\Http\Controllers\Client\QcmController;

// ---------------------------
// Dashboard
// ---------------------------
Route::get('/dashboard', fn() => view('backoffice.dashboard'))
    ->middleware('auth')
    ->name('backoffice.dashboard');

// ---------------------------
// Checklist routes (CRUD)
// ---------------------------
Route::prefix('checklists')->name('checklists.')->middleware('auth')->group(function() {
    Route::get('/', [ChecklistController::class, 'index'])->name('index');
    Route::get('/create', [ChecklistController::class, 'create'])->name('create');
    Route::post('/', [ChecklistController::class, 'store'])->name('store');
    Route::get('/{checklist}/edit', [ChecklistController::class, 'edit'])->name('edit');
    Route::put('/{checklist}', [ChecklistController::class, 'update'])->name('update');
    Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
});

// ---------------------------
// Domain routes (CRUD)
// ---------------------------
Route::prefix('domains')->name('domains.')->middleware('auth')->group(function() {
    Route::get('/', [DomainController::class, 'index'])->name('index');
    Route::get('/create', [DomainController::class, 'create'])->name('create');
    Route::post('/', [DomainController::class, 'store'])->name('store');
    Route::get('/{domain}/edit', [DomainController::class, 'edit'])->name('edit');
    Route::put('/{domain}', [DomainController::class, 'update'])->name('update');
    Route::delete('/{domain}', [DomainController::class, 'destroy'])->name('destroy');
});

// ---------------------------
// Company routes (CRUD) - Only superadmin
// ---------------------------
Route::prefix('companies')->name('backoffice.companies.')->middleware(['auth','role:superadmin'])->group(function() {
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/create', [CompanyController::class, 'create'])->name('create');
    Route::post('/', [CompanyController::class, 'store'])->name('store');
    Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
    Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
    Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
});

// ---------------------------
// User routes (CRUD) - superadmin|admin
// ---------------------------
Route::prefix('users')->name('backoffice.users.')->middleware(['auth','role:superadmin|admin'])->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// ---------------------------
// Company QCM routes - superadmin|admin
// ---------------------------
Route::prefix('company-qcm')->name('backoffice.company-qcm.')->middleware(['auth','role:superadmin|admin'])->group(function() {
    // List all domains for a given company
    Route::get('/{company}/domains', [CompanyQcmController::class, 'listDomains'])->name('listDomains');

    // Show QCM for a specific domain
    Route::get('/{company}/{domain}', [CompanyQcmController::class, 'show'])->name('show');
    Route::post('/{company}/{domain}', [CompanyQcmController::class, 'store'])->name('store');
    Route::get('/{company}/{domain}/results', [CompanyQcmController::class, 'results'])->name('results');
});

// ---------------------------
// Action Plan routes - superadmin|admin
// ---------------------------
Route::prefix('companies/{company}/action-plans')->name('backoffice.action-plans.')->middleware(['auth','role:superadmin|admin'])->group(function () {
    Route::get('/', [UserActionPlanController::class, 'index'])->name('index');
    Route::get('/{attempt}', [UserActionPlanController::class, 'show'])->name('show');
    Route::post('/store', [UserActionPlanController::class, 'store'])->name('store');
});


// ---------------------------
// Client QCM routes - client
// ---------------------------
Route::prefix('client/qcm')->name('client.qcm.')->middleware(['auth','role:client'])->group(function() {
    Route::get('/', [QcmController::class, 'index'])->name('index'); // client.qcm.index
    Route::get('/{checklist}', [QcmController::class, 'show'])->name('show'); // client.qcm.show
    Route::post('/{checklist}/submit', [QcmController::class, 'submit'])->name('submit'); // client.qcm.submit
    Route::get('/attempt/{attempt}/results', [QcmController::class, 'results'])->name('results'); // client.qcm.results
});