<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| REST API Routes (Global Supply Chain Risk Intelligence)
|--------------------------------------------------------------------------
*/

// 1. Data Negara & Perbandingan
Route::get('/countries', [ApiController::class, 'getCountries']);
Route::get('/countries/compare', [ApiController::class, 'compareCountries']);

// 2. Risk Scoring Engine
Route::get('/risk', [ApiController::class, 'getRisk']);

// 3. Dataset Pelabuhan Global
Route::get('/ports', [ApiController::class, 'getPorts']);

// 4. News Intelligence & Sentiment
Route::get('/news', [ApiController::class, 'getNews']);

// 5. Currency / Kurs Mata Uang Realtime
Route::get('/currency', [ApiController::class, 'getCurrency']);

// 6. Analytics Trend (Untuk Grafik Chart.js)
Route::get('/analytics/trend', [ApiController::class, 'getTrendData']);