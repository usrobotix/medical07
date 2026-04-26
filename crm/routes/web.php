<?php

use App\Http\Controllers\CaseBoardController;
use App\Http\Controllers\CaseStatusController;
use App\Http\Controllers\Kb\CountryController as KbCountryController;
use App\Http\Controllers\Kb\CountryDirectionController as KbCountryDirectionController;
use App\Http\Controllers\Kb\MessageTemplateController as KbMessageTemplateController;
use App\Http\Controllers\Kb\NicheController as KbNicheController;
use App\Http\Controllers\Kb\PartnerController as KbPartnerController;
use App\Http\Controllers\Kb\PartnerResearchController as KbPartnerResearchController;
use App\Http\Controllers\Kb\PartnerVerificationController as KbPartnerVerificationController;
use App\Http\Controllers\Kb\VerificationChecklistController as KbVerificationChecklistController;
use App\Http\Controllers\Kb\VerificationChecklistItemController as KbVerificationChecklistItemController;
use App\Http\Controllers\MedicalCaseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cases/board', [CaseBoardController::class, 'index'])->name('cases.board');

    Route::patch('/cases/{case}/pipeline-status', [CaseStatusController::class, 'updatePipeline'])
        ->name('cases.pipeline-status');

    Route::patch('/cases/{case}/service-status', [CaseStatusController::class, 'updateService'])
        ->name('cases.service-status');

    Route::resource('patients', PatientController::class)->only(['index', 'create', 'store']);
    Route::resource('cases', MedicalCaseController::class)->only(['index', 'create', 'store']);

    // KB read (any authenticated)
    // IMPORTANT: ->whereNumber() on every resource prevents /create, /edit, etc.
    // from being matched by the {id} show route (which is registered first).
    Route::prefix('kb')->name('kb.')->group(function () {
        Route::resource('partners', KbPartnerController::class)
            ->only(['index', 'show'])
            ->whereNumber('partner');
        Route::resource('countries', KbCountryController::class)
            ->only(['index', 'show'])
            ->whereNumber('country');
        Route::resource('niches', KbNicheController::class)
            ->only(['index', 'show'])
            ->whereNumber('niche');
        Route::resource('country-directions', KbCountryDirectionController::class)
            ->only(['index', 'show'])
            ->whereNumber('country_direction');
        Route::resource('verification-checklists', KbVerificationChecklistController::class)
            ->only(['index', 'show'])
            ->whereNumber('verification_checklist');
        Route::resource('message-templates', KbMessageTemplateController::class)
            ->only(['index', 'show'])
            ->whereNumber('message_template');

        // keep resource parameter consistent + restrict to numbers
        Route::resource('partner-verifications', KbPartnerVerificationController::class)
            ->only(['index', 'show'])
            ->parameters(['partner-verifications' => 'partnerVerification'])
            ->whereNumber('partnerVerification');
    });

    // KB write (admin/manager)
    Route::prefix('kb')->name('kb.')->middleware('role:admin|manager')->group(function () {
        Route::resource('partners', KbPartnerController::class)->except(['index', 'show']);
        Route::post('partners/{partner}/start-verification', [KbPartnerController::class, 'startVerification'])
            ->name('partners.start-verification');
        Route::get('partners/{partner}/research/edit', [KbPartnerResearchController::class, 'edit'])
            ->name('partners.research.edit')
            ->whereNumber('partner');
        Route::patch('partners/{partner}/research', [KbPartnerResearchController::class, 'update'])
            ->name('partners.research.update')
            ->whereNumber('partner');

        Route::resource('countries', KbCountryController::class)->except(['index', 'show']);
        Route::resource('niches', KbNicheController::class)->except(['index', 'show']);
        Route::resource('country-directions', KbCountryDirectionController::class)->except(['index', 'show']);
        Route::resource('verification-checklists', KbVerificationChecklistController::class)->except(['index', 'show']);

        Route::post('verification-checklists/{verificationChecklist}/items', [KbVerificationChecklistItemController::class, 'store'])
            ->name('verification-checklists.items.store');
        Route::patch('verification-checklists/{verificationChecklist}/items/{item}', [KbVerificationChecklistItemController::class, 'update'])
            ->name('verification-checklists.items.update');
        Route::delete('verification-checklists/{verificationChecklist}/items/{item}', [KbVerificationChecklistItemController::class, 'destroy'])
            ->name('verification-checklists.items.destroy');

        Route::resource('message-templates', KbMessageTemplateController::class)->except(['index', 'show']);

        // IMPORTANT: keep resource parameter consistent + restrict to numbers
        Route::resource('partner-verifications', KbPartnerVerificationController::class)
            ->except(['index', 'show'])
            ->parameters(['partner-verifications' => 'partnerVerification'])
            ->whereNumber('partnerVerification');

        Route::post('partner-verifications/{partnerVerification}/items/update-bulk', [KbPartnerVerificationController::class, 'updateItems'])
            ->name('partner-verifications.items.update-bulk')
            ->whereNumber('partnerVerification');
    });
});

// Admin Technical Section
Route::prefix('admin/technical')->name('admin.technical.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups', [\App\Http\Controllers\Admin\BackupController::class, 'store'])->name('backups.store');
    Route::get('/backups/test-yandex', [\App\Http\Controllers\Admin\BackupController::class, 'testYandex'])->name('backups.test-yandex');
    Route::get('/backups/{backup}/status', [\App\Http\Controllers\Admin\BackupController::class, 'status'])->name('backups.status');
    Route::get('/backups/{backup}/download', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{backup}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backups.destroy');

    Route::get('/schedule', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedule.index');
    Route::patch('/schedule', [\App\Http\Controllers\Admin\ScheduleController::class, 'update'])->name('schedule.update');

    Route::get('/audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');

    Route::get('/logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/download', [\App\Http\Controllers\Admin\LogController::class, 'download'])->name('logs.download');
    Route::post('/logs/clear', [\App\Http\Controllers\Admin\LogController::class, 'clear'])->name('logs.clear');
});

require __DIR__ . '/auth.php';