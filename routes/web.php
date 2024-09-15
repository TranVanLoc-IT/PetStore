<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

Route::get('/home', [App\Http\Controllers\HomeController::class, 'gettest']);
