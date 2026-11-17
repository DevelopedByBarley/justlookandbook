<?php

use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\AdminResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {

    Route::prefix('password')->name('password.')->group(function () {
        Route::get('forgot',  [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
        Route::post('forgot', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('email')->middleware('throttle:admin.password.email');
        Route::get('reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('reset');
        Route::post('reset',        [AdminResetPasswordController::class, 'reset'])->name('update')->middleware('throttle:admin.password.reset');
    });

});
