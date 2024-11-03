<?php

use App\Http\Controllers\ConsultedDataController;
use App\Http\Controllers\EntriesController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/', function () {
    return response()->json([
        "message" => "Fullstack Challenge 🏅 - Dictionary"
    ], 200);
});

Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
});


Route::prefix('/entries/en')->middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('',  [ConsultedDataController::class, 'index']);
    Route::get('/{word}', [EntriesController::class, 'word']);
    Route::post('/{word}/favorite', [EntriesController::class, 'word']);
});


