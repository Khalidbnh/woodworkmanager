<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->get('/auth-test', function() {
    $user = auth()->user();

    return [
        'authenticated' => auth()->check(),
        'user' => $user ? $user->email : 'null',
        'user_id' => $user ? $user->id : 'null',
        'session_id' => session()->getId(),
    ];
});
