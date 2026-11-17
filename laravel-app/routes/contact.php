<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:contact');
