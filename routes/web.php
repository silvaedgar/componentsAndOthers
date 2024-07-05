<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\IsLoggedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, 'home'])->name('home');
Route::get("/home", [HomeController::class, 'home'])->name('home');
Route::get("/contact", [HomeController::class, 'contact'])->name('contact');
Route::get("/about-us", [HomeController::class, 'aboutUs'])->name('about-us');
Route::get("/login", [LoginController::class, 'index'])->name('login');
Route::post("/login", [LoginController::class, 'login'])->name('login');
Route::get("/signup", [LoginController::class, 'register'])->name('signup');
Route::post("/signup", [LoginController::class, 'signup'])->name('signup');


Route::middleware(IsLoggedMiddleware::class)->group(function() {
    Route::post("/logout", [LoginController::class, 'logout'])->name('logout');
    Route::get("/add-data", [DataController::class, 'index'])->name('add-data');
    Route::get("/extendedSession", [LoginController::class, 'extendedSession'])->name('regenerar-sesion');
    Route::resource('users',UsersController::class);
});

