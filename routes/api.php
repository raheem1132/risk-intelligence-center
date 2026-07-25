<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortApiController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\RiskController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\MapDataController;

// ==========================================
// REST API SESUAI SPESIFIKASI PROJECT FINAL
// ==========================================

// 1. ENDPOINT PORTS (CRUD PELABUHAN)
Route::get('/ports', [PortApiController::class, 'index']);          // Get All / Search
Route::get('/ports/{id}', [PortApiController::class, 'show']);      // Get Detail + AI Sentiment Analysis
Route::post('/ports', [PortApiController::class, 'store']);         // Create
Route::put('/ports/{id}', [PortApiController::class, 'update']);     // Update
Route::delete('/ports/{id}', [PortApiController::class, 'destroy']); // Delete

// 2. ENDPOINT COUNTRIES (DATA NEGARA & INDIKATOR)
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}', [CountryController::class, 'show']);

// 3. ENDPOINT RISK ENGINE (SKOR RISIKO)
Route::get('/risk', [RiskController::class, 'index']);

// 4. ENDPOINT NEWS (BERITA & SENTIMENT)
Route::get('/news', [NewsController::class, 'index']);

// 5. ENDPOINT CURRENCY (KURS MATA UANG)
Route::get('/currency', [CurrencyController::class, 'index']);
Route::get('/overview', [DataController::class, 'overview']);
Route::get('/map/ports', [MapDataController::class, 'ports']);
Route::post('/countries/{code}/sync', [DataController::class, 'sync']);
Route::get('/countries/{code}/economy', [DataController::class, 'economy']);
Route::post('/countries/{code}/economy/refresh', [DataController::class, 'refreshEconomy']);
Route::get('/countries/{code}/weather', [DataController::class, 'weather']);
Route::get('/countries/{code}/currency-trend', [DataController::class, 'currency']);
Route::get('/countries/{code}/risk-trend', [DataController::class, 'riskTrend']);
