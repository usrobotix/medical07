<?php

use App\Http\Controllers\CaseBoardController;
use App\Http\Controllers\MedicalCaseController;
use App\Http\Controllers\CaseStatusController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Kb\PartnerController as KbPartnerController;
use App\Http\Controllers\Kb\CountryController as KbCountryController;
use App\Http\Controllers\Kb\NicheController as KbNicheController;
use App\Http\Controllers\Kb\CountryDirectionController as KbCountryDirectionController;
use App\Http\Controllers\Kb\VerificationChecklistController as KbVerificationChecklistController;
use App\Http\Controllers\Kb\MessageTemplateController as KbMessageTemplateController;
use App\Http\Controllers\Kb\PartnerVerificationController as KbPartnerVerificationController;
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

    // Knowledge Base (KB) read-only routes
    Route::prefix('kb')->name('kb.')->group(function () {
        Route::resource('partners', KbPartnerController::class)->only(['index', 'show']);
        Route::resource('countries', KbCountryController::class)->only(['index', 'show']);
        Route::resource('niches', KbNicheController::class)->only(['index', 'show']);
        Route::resource('country-directions', KbCountryDirectionController::class)->only(['index', 'show']);
        Route::resource('verification-checklists', KbVerificationChecklistController::class)->only(['index', 'show']);
        Route::resource('message-templates', KbMessageTemplateController::class)->only(['index', 'show']);
        Route::resource('partner-verifications', KbPartnerVerificationController::class)->only(['index', 'show']);
    });
});

require __DIR__.'/auth.php';