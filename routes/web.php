<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicationsController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/about-us', 'aboutUs')->name('about');
        Route::get('/contact-us', 'contactUs')->name('contact');
        Route::post('/contact-us', 'contactUsSubmit')->name('contact-us');
    });

Route::controller(VerificationController::class)
    ->prefix('verification')
    ->name('verification.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');

        Route::get('/resend', 'resend')->name('resend');
        Route::post('/resend', 'resendPost')->name('resendPost');

});


Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::controller(ProfileController::class)
            ->name('profile.')
            ->group(function () {
                Route::get('/profile', 'edit')->name('edit');
                Route::patch('/profile', 'update')->name('update');
                Route::delete('/profile', 'destroy')->name('destroy');
            });

        Route::controller(MedicationsController::class)
            ->name('medications.')
            ->prefix('medications')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::post('/delete', 'delete')->name('delete');
            });
    });

//Route::resource('offers', MedicationsController::class)->middleware(['auth', 'verified']);
//Route::resource('orders', MedicationsController::class)->middleware(['auth', 'verified']);
//Route::resource('ratings', MedicationsController::class)->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
