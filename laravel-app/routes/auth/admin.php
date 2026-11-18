<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.login'));

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('handbook', [AdminDashboardController::class, 'handbook'])->name('handbook');
        Route::get('editor-demo', fn () => view('admin.editor-demo'))->name('editor.demo');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('password', [AdminProfileController::class, 'editPassword'])->name('password');
            Route::put('password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        });

        require __DIR__.'/../admin/topics.php';
        require __DIR__.'/../admin/countries.php';
        require __DIR__.'/../admin/programs.php';

        Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
