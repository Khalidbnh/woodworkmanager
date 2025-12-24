<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/filament-test', function() {
    // without ->middleware('auth')
    return [
        'authenticated' => auth()->check(),
        'user' => auth()->user()?->email,
        'session_id' => session()->getId(),
    ];
});

Route::redirect('/login', '/admin/login')->name('login');
