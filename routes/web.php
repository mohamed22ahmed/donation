<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicationsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact');
Route::post('/contact-us', [HomeController::class, 'contactUsSubmit'])->name('contact-us');

Route::controller(MedicationsController::class)
    ->name('medications.')
    ->prefix('medications')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
    });

//Route::resource('offers', MedicationsController::class)->middleware(['auth', 'verified']);
//Route::resource('orders', MedicationsController::class)->middleware(['auth', 'verified']);
//Route::resource('ratings', MedicationsController::class)->middleware(['auth', 'verified']);


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
