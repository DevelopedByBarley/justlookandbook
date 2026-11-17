<?php

use App\Http\Controllers\Admin\AdminTopicController;
use Illuminate\Support\Facades\Route;

Route::resource('topics', AdminTopicController::class)->except(['show']);
