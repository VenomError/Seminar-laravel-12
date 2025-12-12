<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::livewire('/', 'pages::landing.home')->name('home');


Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');
});
Route::get('logout', function () {
    Auth::logout();
    Session::invalidate();
    Session::flush();

    return redirect()->route('login');
});
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::livewire('/', 'pages::dashboard.index')->name('dashboard');
});