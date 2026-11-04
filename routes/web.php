<?php

use App\Http\Middleware\ProtectAuthenticatedPageHistory;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified', ProtectAuthenticatedPageHistory::class])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
