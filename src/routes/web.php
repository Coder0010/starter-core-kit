<?php

use Illuminate\Support\Facades\Route;
use Mkamel\StarterCoreKit\App\Models\ExceptionModel;

Route::get('test-package', function () {
    return view('starter-core-kit::exceptions');
});

// Route::resource('exceptions', \Mkamel\StarterCoreKit\App\Http\Controllers\ExceptionController::class);