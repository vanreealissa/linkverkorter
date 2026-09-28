<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LinkController::class, 'index'])->name('home');
Route::post('/links', [LinkController::class, 'store'])->middleware('throttle:20,1')->name('links.store');
Route::get('/links/{link:code}', [LinkController::class, 'show'])->name('links.show');

// Moet als laatste: vangt elke korte code op.
Route::get('/{link:code}', [LinkController::class, 'redirect'])
    ->where('link', '[A-Za-z0-9_-]+')
    ->name('links.redirect');
