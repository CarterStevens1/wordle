<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordleController;



// Route for handling word guesses
Route::middleware(['throttle:guess'])->group(function () {
    Route::post('/guess', [WordleController::class, 'guess']);
});
