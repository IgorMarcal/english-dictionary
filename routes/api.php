<?php

use App\Http\Controllers\ConsultedDataController;
use App\Http\Controllers\EntriesController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json([
        "message" => "Fullstack Challenge 🏅 - Dictionary"
    ], 200);
});

Route::prefix('/auth')->group(function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
});

Route::prefix('/user')->middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('me',  [ConsultedDataController::class, 'user']);
    Route::get('me/history',  [ConsultedDataController::class, 'userHistory']);
    Route::get('me/favorites',  [ConsultedDataController::class, 'userFavorites']);
});


Route::prefix('/entries/en')->middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('',  [ConsultedDataController::class, 'index']);
    Route::get('/{word}', [EntriesController::class, 'word']);
    Route::post('/{word}/favorite', [EntriesController::class, 'word']);
});


