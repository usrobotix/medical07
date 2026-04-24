<?php

use App\Http\Controllers\CaseBoardController;
use App\Http\Controllers\MedicalCaseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
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

    Route::resource('patients', PatientController::class)->only(['index', 'create', 'store']);
    Route::resource('cases', MedicalCaseController::class)->only(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';