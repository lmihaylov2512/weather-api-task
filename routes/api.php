<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\WeatherController;

// prefix API v1 endpoints
Route::prefix('v1')->group(function () {
    // retrieving temperature data for specific city
    Route::get('/weather/{city}', [WeatherController::class, 'show'])->name('api.v1.weather.show');
});
