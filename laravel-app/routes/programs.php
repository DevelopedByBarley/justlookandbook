<?php

use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/programs/{slug}', [ProgramController::class, 'show'])->name('program.show');
