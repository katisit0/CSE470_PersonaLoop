<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaSelectionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});





Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/select-persona', [PersonaSelectionController::class, 'store'])->name('persona.select.store');
    //Route::post('/persona/select/{id}', [PersonaSelectionController::class, 'store'])->name('persona.select');

    Route::get('/select-persona', [PersonaSelectionController::class, 'index'])->name('persona.select');
    Route::post('/select-persona', [PersonaSelectionController::class, 'store'])->name('persona.select');





});

require __DIR__.'/auth.php';
