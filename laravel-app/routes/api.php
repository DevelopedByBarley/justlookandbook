<?php

// ============================================================
// API RÉSZ — ezt a fájlt másold át a saját szerveredre
// ============================================================

use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;

Route::get('/license/{key}', [LicenseController::class, 'check']);
