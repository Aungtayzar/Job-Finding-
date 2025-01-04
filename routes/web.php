<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class,'index']);

Route::resource('/jobs', JobController::class);
