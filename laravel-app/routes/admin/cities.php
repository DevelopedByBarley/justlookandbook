<?php

use App\Http\Controllers\Admin\AdminCityController;
use Illuminate\Support\Facades\Route;

Route::resource('cities', AdminCityController::class)->except(['show']);
