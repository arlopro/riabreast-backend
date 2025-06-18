<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RehabController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Laravel API is working!']);
});

// 🔓 Rotte pubbliche (no auth)
Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

// 🔐 Rotte protette (token Bearer)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);

    Route::controller(RehabController::class)->group(function () {
        Route::get('period', 'currentPeriod');
        Route::get('questionnaire', 'questionnaire');
        Route::get('extras', 'extras');
        Route::get('extra/{id}', 'extraDetail');

        Route::post('period/previous', 'goToPreviousPeriod');
        Route::get('sessions/daily', 'dailySessions');
        Route::post('questionnaire/submit', 'submitAnswers');
        Route::post('session/start', 'startSession');
        Route::post('session/end', 'endSession');
    });

    Route::controller(FaqController::class)->group(function () {
        Route::get('faqs', 'index');
    });
});
