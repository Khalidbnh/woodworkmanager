<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/filament-test', function() {
    return [
        'panel_exists' => class_exists(\Filament\Facades\Filament::class),
        'panels' => \Filament\Facades\Filament::getPanels(),
        'user' => auth()->user()?->email,
    ];
});
