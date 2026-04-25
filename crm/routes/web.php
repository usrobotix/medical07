<?php

use App\Http\Controllers\CaseBoardController;
use App\Http\Controllers\MedicalCaseController;
use App\Http\Controllers\CaseStatusController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Kb\PartnerController;
use App\Http\Controllers\Kb\CountryController;
use App\Http\Controllers\Kb\NicheController;
use App\Http\Controllers\Kb\CountryDirectionController;
use App\Http\Controllers\Kb\VerificationChecklistController;
use App\Http\Controllers\Kb\MessageTemplateController;
use App\Http\Controllers\Kb\PartnerVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cases/board', [CaseBoardController::class, 'index'])->name('cases.board');

    // Pipeline status drag&drop endpoint (JSON)
    Route::patch('/cases/{case}/pipeline-status', [CaseStatusController::class, 'updatePipeline'])
        ->name('cases.pipeline-status');

    // Service status overlay endpoint (JSON)
    Route::patch('/cases/{case}/service-status', [CaseStatusController::class, 'updateService'])
        ->name('cases.service-status');

    Route::resource('patients', PatientController::class)->only(['index', 'create', 'store']);
    Route::resource('cases', MedicalCaseController::class)->only(['index', 'create', 'store']);

    // Knowledge Base (read-only)
    Route::resource('partners', PartnerController::class)->only(['index', 'show']);
    Route::resource('countries', CountryController::class)->only(['index', 'show']);
    Route::resource('niches', NicheController::class)->only(['index', 'show']);
    Route::resource('country-directions', CountryDirectionController::class)->only(['index', 'show']);
    Route::resource('verification-checklists', VerificationChecklistController::class)->only(['index', 'show']);
    Route::resource('message-templates', MessageTemplateController::class)->only(['index', 'show']);
    Route::resource('partner-verifications', PartnerVerificationController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';