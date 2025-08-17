<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaSelectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;


Route::get('/', function () {
    return view('welcome');
});





Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit');
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::post('/select-persona', [PersonaSelectionController::class, 'store'])->name('persona.select.store');
    //Route::post('/persona/select/{id}', [PersonaSelectionController::class, 'store'])->name('persona.select');

    Route::get('/select-persona', [PersonaSelectionController::class, 'index'])->name('persona.select');
    Route::post('/select-persona', [PersonaSelectionController::class, 'store'])->name('persona.select');

    Route::get('/journal', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');



});

require __DIR__.'/auth.php';
