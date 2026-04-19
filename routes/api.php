<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::prefix('items')->group(function () {
    Route::get('/',        [ItemController::class, 'index']);
    Route::get('/{id}',    [ItemController::class, 'show']);
    Route::post('/',       [ItemController::class, 'store']);
    Route::put('/{id}',    [ItemController::class, 'update']);
    Route::patch('/{id}',  [ItemController::class, 'patch']);
    Route::delete('/{id}', [ItemController::class, 'destroy']);
});