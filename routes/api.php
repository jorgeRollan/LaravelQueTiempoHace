<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WeatherController;

// Rutas de llamada a la api de openWeather
Route::get('/weatherLocation', [WeatherController::class, 'getWeatherByLocation']);
Route::get('/weatherForecast', [WeatherController::class, 'getWeatherForecast']);
Route::get('/weatherForecastHourly', [WeatherController::class, 'getWeatherForecastHourly']);
Route::get('/weatherCity', [WeatherController::class, 'getWeatherByCity']);
Route::get('/geoCountry', [WeatherController::class, 'getLocationCountry']);