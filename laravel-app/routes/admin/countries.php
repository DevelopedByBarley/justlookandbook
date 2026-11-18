<?php

use App\Http\Controllers\Admin\AdminCountryController;
use Illuminate\Support\Facades\Route;

Route::resource('countries', AdminCountryController::class)->except(['show']);
