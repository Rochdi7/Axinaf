<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\ChecklistController;
use App\Http\Controllers\Frontoffice\QcmController;

Route::get('/dashboard', fn() => view('backoffice.dashboard'))->name('backoffice.dashboard');

// Checklist routes (CRUD)
Route::prefix('checklists')->name('checklists.')->group(function() {
    Route::get('/', [ChecklistController::class, 'index'])->name('index');
    Route::get('/create', [ChecklistController::class, 'create'])->name('create');
    Route::post('/', [ChecklistController::class, 'store'])->name('store');
    Route::get('/{checklist}/edit', [ChecklistController::class, 'edit'])->name('edit');
    Route::put('/{checklist}', [ChecklistController::class, 'update'])->name('update');
    Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function() {
    Route::get('/qcm', [QcmController::class, 'index'])->name('qcm.index');
    Route::get('/qcm/{checklist}', [QcmController::class, 'show'])->name('qcm.show');
    Route::post('/qcm/{checklist}/submit', [QcmController::class, 'submit'])->name('qcm.submit');
});
