<?php

use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('media.show');
});
