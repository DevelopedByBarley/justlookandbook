<?php

use App\Http\Controllers\Admin\AdminProgramController;
use Illuminate\Support\Facades\Route;

Route::get('programs/order', [AdminProgramController::class, 'order'])->name('programs.order');
Route::post('programs/reorder', [AdminProgramController::class, 'reorder'])->name('programs.reorder');

Route::resource('programs', AdminProgramController::class)->except(['show']);
