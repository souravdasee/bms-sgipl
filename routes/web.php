<?php

use App\Http\Controllers\AccountantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SAController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);

Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/organizers', [SAController::class, 'organizer'])->middleware('can:Admin', 'auth', 'verified')->name('organizer');

Route::get('/accountant', [AccountantController::class, 'index'])->middleware('can:Accountant', 'auth', 'verified')->name('accountant');
Route::get('/invoice', [AccountantController::class, 'invoice'])->middleware('can:Accountant', 'auth', 'verified')->name('invoice');
Route::post('/showinvoice', [AccountantController::class, 'show'])->middleware('can:Accountant', 'auth', 'verified')->name('showinvoice');
