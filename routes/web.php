<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class,'index'])->name('home');

Route::resource('/jobs', JobController::class);

Route::get('/register', [RegisterController::class,'register'])->name('register');
Route::post('/register', [RegisterController::class,'store'])->name('register.store');


Route::get('/login', [LoginController::class,'login'])->name('login');
Route::post('/login', [LoginController::class,'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

