<?php

use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:30,1')->group(function () {
    Route::post('/links', [LinkController::class, 'store'])->name('api.links.store');
    Route::get('/links/{link:code}', [LinkController::class, 'show'])->name('api.links.show');
});
